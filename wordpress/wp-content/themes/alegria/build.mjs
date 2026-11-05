/**
 * Builds the theme's CSS and JS.
 *
 *   node build.mjs          Production build.
 *   node build.mjs --watch  Development build that rebuilds on change.
 *   node build.mjs --zip    Production build, then a zip of only the files the site needs at runtime.
 *
 * Theme assets:  assets/css/*.css (files not starting with "_") and assets/js/*.{js,ts} -> assets/dist/
 * Blocks:        assets/blocks/<slug>/{index,view}.js and {style,editor}.css -> assets/blocks/<slug>/build/
 *
 * In block scripts, @wordpress/* and React imports are not bundled. They resolve to the globals WordPress
 * provides (wp.blockEditor, React, ...), and each block script gets an <entry>.asset.php listing the
 * matching script handles, so WordPress loads them first. The same goes for `swiper`: block scripts get the
 * window.Swiper global from assets/js/swiper.js (script handle `alegria-swiper`), which bundles the real package.
 */
import crypto from 'node:crypto';
import fs from 'node:fs';
import path from 'node:path';
import browserslist from 'browserslist';
import esbuild from 'esbuild';
import { browserslistToTargets, bundleAsync } from 'lightningcss';

const root = path.dirname( new URL( import.meta.url ).pathname );
const watch = process.argv.includes( '--watch' );
const zip = process.argv.includes( '--zip' );

const dist = path.join( root, 'assets/dist' );
const blocksDir = path.join( root, 'assets/blocks' );

const blockDirs = fs
	.readdirSync( blocksDir )
	.map( ( name ) => path.join( blocksDir, name ) )
	.filter( ( dir ) => fs.existsSync( path.join( dir, 'block.json' ) ) );

const files = ( dir, test ) =>
	fs.readdirSync( dir ).filter( test ).map( ( name ) => path.join( dir, name ) );

const log = ( message ) =>
	console.log( `[${ new Date().toLocaleTimeString() }] ${ message }` );

// ---------------------------------------------------------------------------
// JavaScript
// ---------------------------------------------------------------------------

/**
 * Maps an external import to the global WordPress exposes and its script handle.
 *
 * @param {string} request Import path, e.g. '@wordpress/block-editor'.
 * @return {{global: string, handle: string}} Global and handle.
 */
function wpExternal( request ) {
	if ( request === 'swiper' ) {
		return { global: 'Swiper', handle: 'alegria-swiper' };
	}
	if ( request === 'react/jsx-runtime' ) {
		return { global: 'ReactJSXRuntime', handle: 'react-jsx-runtime' };
	}
	if ( request === 'react' || request === 'react-dom' ) {
		return { global: request === 'react' ? 'React' : 'ReactDOM', handle: request };
	}
	const name = request.slice( '@wordpress/'.length );
	return {
		global: 'wp.' + name.replace( /-([a-z])/g, ( _, c ) => c.toUpperCase() ),
		handle: 'wp-' + name,
	};
}

/**
 * esbuild plugin: resolves WordPress/React imports to globals and writes <entry>.asset.php
 * with the script handles the entry depends on.
 *
 * @param {string} assetFile Path of the .asset.php file to write, or null for none.
 * @param {boolean} externalSwiper Whether `swiper` resolves to the shared global instead of being bundled.
 */
function wpGlobals( assetFile, externalSwiper ) {
	return {
		name: 'wp-globals',
		setup( build ) {
			let handles = new Set();

			build.onStart( () => {
				handles = new Set();
			} );

			const filter = externalSwiper
				? /^(@wordpress\/|react(-dom)?(\/jsx-runtime)?$|swiper$)/
				: /^(@wordpress\/|react(-dom)?(\/jsx-runtime)?$)/;

			build.onResolve( { filter }, ( args ) => {
				const external = wpExternal( args.path );
				handles.add( external.handle );
				return { path: external.global, namespace: 'wp-global' };
			} );

			build.onLoad( { filter: /.*/, namespace: 'wp-global' }, ( args ) => ( {
				contents: `module.exports = window.${ args.path };`,
				loader: 'js',
			} ) );

			build.onEnd( ( result ) => {
				if ( result.errors.length || ! assetFile ) {
					return;
				}
				const outfile = build.initialOptions.outfile;
				const version = crypto.createHash( 'md5' ).update( fs.readFileSync( outfile ) ).digest( 'hex' ).slice( 0, 20 );
				const deps = [ ...handles ].sort().map( ( h ) => `'${ h }'` ).join( ', ' );
				fs.writeFileSync( assetFile, `<?php return array( 'dependencies' => array( ${ deps } ), 'version' => '${ version }' );\n` );
				log( `built ${ path.relative( root, outfile ) }` );
			} );
		},
	};
}

const jsEntries = [
	...files( path.join( root, 'assets/js' ), ( f ) => /\.(js|ts)$/.test( f ) ).map( ( entry ) => ( {
		entry,
		outfile: path.join( dist, path.basename( entry ).replace( /\.ts$/, '.js' ) ),
		assetFile: null,
	} ) ),
	...blockDirs.flatMap( ( dir ) =>
		[ 'index', 'view' ]
			.filter( ( name ) => fs.existsSync( path.join( dir, `${ name }.js` ) ) )
			.map( ( name ) => ( {
				entry: path.join( dir, `${ name }.js` ),
				outfile: path.join( dir, 'build', `${ name }.js` ),
				assetFile: path.join( dir, 'build', `${ name }.asset.php` ),
			} ) )
	),
];

async function buildJs() {
	for ( const { entry, outfile, assetFile } of jsEntries ) {
		const options = {
			entryPoints: [ entry ],
			outfile,
			bundle: true,
			format: 'iife',
			target: 'es2020',
			minify: ! watch,
			sourcemap: watch ? 'inline' : false,
			jsx: 'automatic',
			loader: { '.js': 'jsx' },
			logLevel: 'warning',
			plugins: [ wpGlobals( assetFile, assetFile !== null ) ],
		};

		if ( watch ) {
			const ctx = await esbuild.context( options );
			await ctx.watch();
		} else {
			await esbuild.build( options );
		}
	}
}

// ---------------------------------------------------------------------------
// CSS
// ---------------------------------------------------------------------------

const cssTargets = browserslistToTargets( browserslist( null, { path: root } ) );

const cssEntries = [
	...files( path.join( root, 'assets/css' ), ( f ) => f.endsWith( '.css' ) && ! f.startsWith( '_' ) ).map( ( entry ) => ( {
		entry,
		outfile: path.join( dist, path.basename( entry ) ),
	} ) ),
	...blockDirs.flatMap( ( dir ) =>
		[ 'style.css', 'editor.css' ]
			.filter( ( name ) => fs.existsSync( path.join( dir, name ) ) )
			.map( ( name ) => ( { entry: path.join( dir, name ), outfile: path.join( dir, 'build', name ) } ) )
	),
];

async function buildCss() {
	await Promise.all(
		cssEntries.map( async ( { entry, outfile } ) => {
			try {
				const { code } = await bundleAsync( {
					filename: entry,
					minify: ! watch,
					targets: cssTargets,
					drafts: { customMedia: true },
				} );
				fs.mkdirSync( path.dirname( outfile ), { recursive: true } );
				fs.writeFileSync( outfile, code );
			} catch ( error ) {
				const where = error.loc ? `${ path.relative( root, error.fileName ) }:${ error.loc.line }:${ error.loc.column }` : path.relative( root, entry );
				console.error( `✘ CSS error in ${ where }: ${ error.message }` );
				if ( ! watch ) {
					process.exitCode = 1;
				}
			}
		} )
	);
}

/**
 * Rebuilds all CSS when any .css file under assets/ changes.
 *
 * Polls modification times instead of using fs.watch, because file events
 * do not reliably reach the container through the Docker bind mount.
 */
function watchCss() {
	const cssFiles = () =>
		fs.readdirSync( path.join( root, 'assets' ), { recursive: true } )
			.filter( ( f ) => f.endsWith( '.css' ) && ! f.includes( 'build/' ) && ! f.startsWith( 'dist/' ) )
			.map( ( f ) => path.join( root, 'assets', f ) );
	const snapshot = () => cssFiles().map( ( f ) => `${ f }:${ fs.statSync( f ).mtimeMs }` ).join( '|' );

	let last = snapshot();
	setInterval( async () => {
		const current = snapshot();
		if ( current !== last ) {
			last = current;
			await buildCss();
			log( 'built CSS' );
		}
	}, 300 );
}

// ---------------------------------------------------------------------------
// Zip
// ---------------------------------------------------------------------------

async function buildZip() {
	const { default: archiver } = await import( 'archiver' );
	const slug = path.basename( root );
	const zipPath = path.join( root, `${ slug }.zip` );
	const archive = archiver( 'zip' );
	archive.pipe( fs.createWriteStream( zipPath ) );

	const runtimeFiles = [
		'*.php',
		'style.css',
		'theme.json',
		'screenshot.*',
		'inc/**',
		'template-parts/**',
		'assets/dist/**',
		'assets/svg/**',
		'assets/fonts/**',
		'assets/blocks/*/block.json',
		'assets/blocks/*/render.php',
		'assets/blocks/*/build/**',
	];
	for ( const pattern of runtimeFiles ) {
		archive.glob( pattern, { cwd: root }, { prefix: slug } );
	}

	await archive.finalize();
	log( `wrote ${ path.relative( root, zipPath ) } (${ ( archive.pointer() / 1024 ).toFixed( 0 ) } KB)` );
}

// ---------------------------------------------------------------------------

fs.rmSync( dist, { recursive: true, force: true } );
for ( const dir of blockDirs ) {
	fs.rmSync( path.join( dir, 'build' ), { recursive: true, force: true } );
}

await Promise.all( [ buildJs(), buildCss() ] );

if ( watch ) {
	watchCss();
	log( 'watching for changes (Ctrl+C to stop)' );
} else if ( zip ) {
	await buildZip();
}
