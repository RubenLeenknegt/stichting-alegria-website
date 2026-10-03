<?php
/**
 * Newsletter and Event post types, and the event date field.
 *
 * @package alegria
 */

add_action(
	'init',
	function () {
		register_post_type(
			'newsletter',
			array(
				'labels'        => array(
					'name'               => 'Nieuwsbrieven',
					'singular_name'      => 'Nieuwsbrief',
					'add_new'            => 'Nieuwe toevoegen',
					'add_new_item'       => 'Nieuwe nieuwsbrief toevoegen',
					'edit_item'          => 'Nieuwsbrief bewerken',
					'new_item'           => 'Nieuwe nieuwsbrief',
					'view_item'          => 'Nieuwsbrief bekijken',
					'view_items'         => 'Nieuwsbrieven bekijken',
					'search_items'       => 'Nieuwsbrieven zoeken',
					'not_found'          => 'Geen nieuwsbrieven gevonden.',
					'not_found_in_trash' => 'Geen nieuwsbrieven gevonden in de prullenbak.',
					'all_items'          => 'Alle nieuwsbrieven',
					'menu_name'          => 'Nieuwsbrieven',
					'name_admin_bar'     => 'Nieuwsbrief',
				),
				'public'        => true,
				'show_in_rest'  => true,
				'has_archive'   => true,
				'rewrite'       => array(
					'slug'       => 'newsletters',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'menu_icon'     => 'dashicons-email-alt',
				'menu_position' => 5,
			)
		);

		register_post_type(
			'event',
			array(
				'labels'        => array(
					'name'               => 'Evenementen',
					'singular_name'      => 'Evenement',
					'add_new'            => 'Nieuw toevoegen',
					'add_new_item'       => 'Nieuw evenement toevoegen',
					'edit_item'          => 'Evenement bewerken',
					'new_item'           => 'Nieuw evenement',
					'view_item'          => 'Evenement bekijken',
					'view_items'         => 'Evenementen bekijken',
					'search_items'       => 'Evenementen zoeken',
					'not_found'          => 'Geen evenementen gevonden.',
					'not_found_in_trash' => 'Geen evenementen gevonden in de prullenbak.',
					'all_items'          => 'Alle evenementen',
					'menu_name'          => 'Evenementen',
					'name_admin_bar'     => 'Evenement',
				),
				'public'        => true,
				'show_in_rest'  => true,
				'has_archive'   => true,
				'rewrite'       => array(
					'slug'       => 'events',
					'with_front' => false,
				),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'menu_icon'     => 'dashicons-calendar-alt',
				'menu_position' => 5,
			)
		);

		register_post_meta(
			'event',
			'event_date',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
);

add_action(
	'add_meta_boxes_event',
	function () {
		add_meta_box( 'event_details', 'Evenementdetails', 'alegria_render_event_details_meta_box', 'event', 'side', 'high' );
	}
);

/**
 * Renders the event date field in the event editor sidebar.
 *
 * @param WP_Post $post The event being edited.
 */
function alegria_render_event_details_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'event_details_save', 'event_details_nonce' );
	$event_date = get_post_meta( $post->ID, 'event_date', true );
	?>
	<p>
		<label for="event_date"><strong>Datum</strong></label><br>
		<input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" style="width:100%">
	</p>
	<?php
}

add_action(
	'save_post_event',
	function ( int $post_id ) {
		if ( ! isset( $_POST['event_details_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['event_details_nonce'] ), 'event_details_save' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['event_date'] ) ) {
			update_post_meta( $post_id, 'event_date', sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) );
		}
	}
);

// Order the event archive by event date, newest first.
add_action(
	'pre_get_posts',
	function ( WP_Query $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'event' ) ) {
			$query->set( 'meta_key', 'event_date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'DESC' );
		}
	}
);
