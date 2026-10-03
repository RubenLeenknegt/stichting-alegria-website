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
		'rewrite'             => [ 'slug' => 'newsletters', 'with_front' => false ],
		'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		'menu_icon'           => 'dashicons-email-alt',
		'menu_position'       => 5,
		'capability_type'     => 'post',  // Reuses standard post capabilities
	] );
} );

// ---------------------------------------------------------------------------
// Register Event custom post type
// ---------------------------------------------------------------------------

add_action( 'init', function () {
	register_post_type( 'event', [
		'labels' => [
			'name'                  => 'Events',
			'singular_name'         => 'Event',
			'add_new'               => 'Add New',
			'add_new_item'          => 'Add New Event',
			'edit_item'             => 'Edit Event',
			'new_item'              => 'New Event',
			'view_item'             => 'View Event',
			'view_items'            => 'View Events',
			'search_items'          => 'Search Events',
			'not_found'             => 'No events found.',
			'not_found_in_trash'    => 'No events found in trash.',
			'all_items'             => 'All Events',
			'menu_name'             => 'Events',
			'name_admin_bar'        => 'Event',
		],
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => true,
		'query_var'           => true,
		'rewrite'             => [ 'slug' => 'events', 'with_front' => false ],
		'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
		'menu_icon'           => 'dashicons-calendar-alt',
		'menu_position'       => 5,
		'capability_type'     => 'post',
	] );
} );

add_action( 'init', function () {
	register_post_meta( 'event', 'event_date', [
		'type'              => 'string',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	] );
} );

// Meta box UI.
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'event_details',
		'Event details',
		__NAMESPACE__ . '\\render_event_details_meta_box',
		'event',
		'side',
		'high'
	);
} );

function render_event_details_meta_box( $post ) {
	wp_nonce_field( 'event_details_save', 'event_details_nonce' );
	$event_date = get_post_meta( $post->ID, 'event_date', true );
	?>
	<p>
		<label for="event_date"><strong><?php esc_html_e( 'Event date', 'wp-rig' ); ?></strong></label><br>
		<input
			type="date"
			id="event_date"
			name="event_date"
			value="<?php echo esc_attr( $event_date ); ?>"
			style="width:100%"
		>
	</p>
	<?php
}

add_action( 'save_post_event', function ( $post_id ) {
	if ( ! isset( $_POST['event_details_nonce'] ) || ! wp_verify_nonce( $_POST['event_details_nonce'], 'event_details_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['event_date'] ) ) {
		update_post_meta( $post_id, 'event_date', sanitize_text_field( $_POST['event_date'] ) );
	}
} );

add_action( 'pre_get_posts', function ( $query ) {
	if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'event' ) ) {
		$query->set( 'meta_key', 'event_date' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
	}
} );

/**
 * Autoloader for the WP_Rig\WP_Rig classes in inc/.
 *
 * @param string $class_name Class name to load.
 */
spl_autoload_register(
	function ( $class_name ) {
		$namespace = 'WP_Rig\\WP_Rig\\';
		if ( 0 !== strpos( $class_name, $namespace ) ) {
			return;
		}

		$path = get_template_directory() . '/inc/' . str_replace( '\\', '/', substr( $class_name, strlen( $namespace ) ) ) . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
);

// Load the `wp_rig()` entry point function and initialize the theme.
require get_template_directory() . '/inc/functions.php';
call_user_func( 'WP_Rig\WP_Rig\wp_rig' );
