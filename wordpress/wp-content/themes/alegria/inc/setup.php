<?php
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );

		// After adding or changing a size, regenerate thumbnails for existing uploads.
		add_image_size( 'alegria-card', 900, 600, true );
		add_image_size( 'alegria-hero', 1920, 1080, true );
		add_image_size( 'alegria-portrait', 600, 800, true );
		add_image_size( 'alegria-square', 600, 600, true );
		add_image_size( 'alegria-wide', 1200, 600, true );
		add_image_size( 'alegria-feature', 1200, 675, true );
		add_image_size( 'alegria-logo', 9999, 160, false );
		add_image_size( 'alegria-content', 1600, 9999, false );

		add_theme_support( 'html5', array( 'style', 'script' ) );
		add_theme_support( 'editor-styles' );

		// Turns on Rank Math's breadcrumbs (and their schema) regardless of its settings.
		add_theme_support( 'rank-math-breadcrumbs' );

		remove_theme_support( 'block-templates' );
		add_theme_support( 'disable-site-editor' );

		register_nav_menus(
			array(
				'primary'         => esc_html__( 'Hoofdmenu', 'alegria' ),
				'footer-info'     => esc_html__( 'Footer: over ons', 'alegria' ),
				'footer-projects' => esc_html__( 'Footer: projecten', 'alegria' ),
				'footer-contact'  => esc_html__( 'Footer: contact', 'alegria' ),
			)
		);
	}
);

// Breadcrumbs always end in the current page's title, even if Rank Math's "Hide Post Title" setting is on.
add_filter(
	'rank_math/frontend/breadcrumb/settings',
	function ( array $settings ): array {
		$settings['remove_title'] = false;
		return $settings;
	}
);

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
