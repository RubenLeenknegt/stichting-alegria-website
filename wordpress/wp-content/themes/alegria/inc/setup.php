<?php
/**
 * Theme supports, menus, and switching off WordPress features this site does not use
 * (site editor, comments, and the default Posts type).
 *
 * @package alegria
 */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

		// Block editor.
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );

		// No site editor or block templates; this is a classic theme.
		remove_theme_support( 'block-templates' );
		add_theme_support( 'disable-site-editor' );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Hoofdmenu', 'alegria' ),
			)
		);
	}
);

// ---------------------------------------------------------------------------
// Disable comments
// ---------------------------------------------------------------------------

add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 10 );
add_filter( 'feed_links_show_comments_feed', '__return_false' );

add_action(
	'init',
	function () {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	},
	100
);

// ---------------------------------------------------------------------------
// Disable the default Posts type
// ---------------------------------------------------------------------------

add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
	}
);

add_action(
	'admin_init',
	function () {
		global $pagenow;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_posts_screen = 'edit.php' === $pagenow && ( empty( $_GET['post_type'] ) || 'post' === $_GET['post_type'] );
		if ( $is_posts_screen || 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit;
		}

		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_posts', 'dashboard', 'normal' );
	}
);

add_action(
	'admin_bar_menu',
	function ( WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
		$wp_admin_bar->remove_node( 'comments' );
	},
	999
);

// The blog index has no posts of its own, so show newsletters there instead.
add_action(
	'pre_get_posts',
	function ( WP_Query $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
			$query->set( 'post_type', 'newsletter' );
		}
	}
);
