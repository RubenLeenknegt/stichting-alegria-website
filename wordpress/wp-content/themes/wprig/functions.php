<?php
// Disable site editor
add_action( 'after_setup_theme', function () {
	remove_theme_support( 'block-templates' );
	add_theme_support( 'disable-site-editor' );
} );

// Remove logo controls from wp admin
function remove_customizer_logo_control( $wp_customize ): void
{
	// Remove the site logo section
	$wp_customize->remove_control( 'custom_logo' );
}
add_action( 'customize_register', 'remove_customizer_logo_control', 20 );

// Disable comments completely
function wprig_disable_comments() {
	// Close comments on the front-end
	add_filter('comments_open', '__return_false', 20, 2);
	add_filter('pings_open', '__return_false', 20, 2);

	// Hide existing comments
	add_filter('comments_array', '__return_empty_array', 10, 2);

	// Remove comments page in menu
	add_action('admin_menu', function() {
		remove_menu_page('edit-comments.php');
	});

	// Redirect any user trying to access comments page
	add_action('admin_init', function() {
		global $pagenow;
		if ($pagenow === 'edit-comments.php') {
			wp_redirect(admin_url());
			exit;
		}
	});

	// Remove comments metabox from dashboard
	add_action('admin_init', function() {
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	});

	// Disable support for comments and trackbacks in post types
	add_action('admin_init', function() {
		$post_types = get_post_types();
		foreach ($post_types as $post_type) {
			if (post_type_supports($post_type, 'comments')) {
				remove_post_type_support($post_type, 'comments');
				remove_post_type_support($post_type, 'trackbacks');
			}
		}
	});
}
add_action('init', 'wprig_disable_comments');

// Remove comment-related links from HTTP headers
add_filter('feed_links_show_comments_feed', '__return_false');

// ---------------------------------------------------------------------------
// Disable the default Posts post type
// ---------------------------------------------------------------------------

// Remove Posts from the admin menu
add_action( 'admin_menu', function () {
	remove_menu_page( 'edit.php' );
} );

// Redirect any direct attempt to access the Posts screens
add_action( 'admin_init', function () {
	global $pagenow;
	if ( $pagenow === 'edit.php' && ( empty( $_GET['post_type'] ) || $_GET['post_type'] === 'post' ) ) {
		wp_redirect( admin_url() );
		exit;
	}
} );

// Remove Posts-related dashboard widgets
add_action( 'admin_init', function () {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts',  'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_posts',   'dashboard', 'normal' );
} );

// Remove the Posts admin bar node
add_action( 'admin_bar_menu', function ( WP_Admin_Bar $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'new-post' );
}, 999 );

// Exclude default posts from front-end queries (belt-and-suspenders)
add_action( 'pre_get_posts', function ( WP_Query $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
		$query->set( 'post_type', 'newsletter' );
	}
} );

// ---------------------------------------------------------------------------
// Register Newsletter custom post type
// ---------------------------------------------------------------------------

add_action( 'init', function () {
	register_post_type( 'newsletter', [
		'labels' => [
			'name'                  => 'Newsletters',
			'singular_name'         => 'Newsletter',
			'add_new'               => 'Add New',
			'add_new_item'          => 'Add New Newsletter',
			'edit_item'             => 'Edit Newsletter',
			'new_item'              => 'New Newsletter',
			'view_item'             => 'View Newsletter',
			'view_items'            => 'View Newsletters',
			'search_items'          => 'Search Newsletters',
			'not_found'             => 'No newsletters found.',
			'not_found_in_trash'    => 'No newsletters found in trash.',
			'all_items'             => 'All Newsletters',
			'menu_name'             => 'Newsletter',
			'name_admin_bar'        => 'Newsletter',
		],
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,   // Required for Gutenberg and REST API
		'has_archive'         => true,
		'query_var'           => true,
		'rewrite'             => [ 'slug' => 'newsletter', 'with_front' => false ],
		'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		'menu_icon'           => 'dashicons-email-alt',
		'menu_position'       => 5,
		'capability_type'     => 'post',  // Reuses standard post capabilities
	] );
} );

/**
 * WP Rig functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wp_rig
 */

/**
 * Add LiveReload script in development mode.
 */

define( 'WP_RIG_MINIMUM_WP_VERSION', '5.4' );
define( 'WP_RIG_MINIMUM_PHP_VERSION', '8.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], WP_RIG_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), WP_RIG_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @access private
	 *
	 * @param string $class_name Class name to load.
	 * @return bool True if the class was loaded, false otherwise.
	 */
	function _wp_rig_autoload( $class_name ) {
		$namespace = 'WP_Rig\WP_Rig';

		if ( 0 !== strpos( $class_name, $namespace . '\\' ) ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( '_wp_rig_autoload' );
}

// Load the `wp_rig()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Add custom WP CLI commands.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once get_template_directory() . '/wp-cli/wp-rig-commands.php';
}

// Initialize the theme.
call_user_func( 'WP_Rig\WP_Rig\wp_rig' );

// @dev-only:start
/**
 * Load development-only helpers (LiveReload for dev proxy).
 * This file resides under optional/ and is not bundled for production.
 */
$__wprig_dev_helpers = get_template_directory() . '/optional/dev/dev-proxy-livereload.php';
if ( file_exists( $__wprig_dev_helpers ) ) {
	require_once $__wprig_dev_helpers;
}
// @dev-only:end
