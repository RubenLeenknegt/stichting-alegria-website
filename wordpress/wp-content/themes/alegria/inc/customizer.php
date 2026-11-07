<?php
/**
 * Customizer settings: the footer, the newsletter and event archive headers and the newsletter popup.
 *
 * Appearance → Customize. Empty fields are left out of the page.
 *
 * @package alegria
 */

declare( strict_types=1 );

/**
 * The Customizer sections, as section ID => array( title, description, settings ).
 * Settings are setting ID => array( label, type ).
 *
 * Types: 'text', 'textarea', 'url', and 'link': a URL in a text field, so an anchor such as
 * #nieuwsbrief-popup (which opens the newsletter popup) isn't marked invalid by the browser.
 *
 * @return array<string, array{0: string, 1: string, 2: array<string, array{0: string, 1: string}>}>
 */
function alegria_customizer_sections(): array {
	$link_hint = __( 'Gebruik #nieuwsbrief-popup als link om de nieuwsbrief-popup te openen.', 'alegria' );

	return array(
		'alegria_footer'             => array(
			__( 'Footer', 'alegria' ),
			__( 'Lege velden worden niet getoond. De menukolommen stel je in onder Menu\'s (Footer: over ons, projecten en contact); de kolomtitel is de naam van het menu.', 'alegria' ) . ' ' . $link_hint,
			array(
				'alegria_footer_text'      => array( __( 'Introtekst', 'alegria' ), 'textarea' ),
				'alegria_social_instagram' => array( __( 'Instagram-URL', 'alegria' ), 'url' ),
				'alegria_social_facebook'  => array( __( 'Facebook-URL', 'alegria' ), 'url' ),
				'alegria_social_linkedin'  => array( __( 'LinkedIn-URL', 'alegria' ), 'url' ),
				'alegria_footer_cta_text'  => array( __( 'Knop: tekst', 'alegria' ), 'text' ),
				'alegria_footer_cta_url'   => array( __( 'Knop: link', 'alegria' ), 'link' ),
				'alegria_footer_link_text' => array( __( 'Tekstlink: tekst', 'alegria' ), 'text' ),
				'alegria_footer_link_url'  => array( __( 'Tekstlink: link', 'alegria' ), 'link' ),
			),
		),
		'alegria_archive_newsletter' => array(
			__( 'Archief: nieuwsbrieven', 'alegria' ),
			__( 'De kop boven de nieuwsbrieven (/nieuwsbrief/). Zonder titel wordt "Nieuwsbrieven" getoond; lege tekst en een knop zonder tekst of link worden weggelaten.', 'alegria' ) . ' ' . $link_hint,
			array(
				'alegria_archive_newsletter_title'       => array( __( 'Titel', 'alegria' ), 'text' ),
				'alegria_archive_newsletter_text'        => array( __( 'Tekst', 'alegria' ), 'textarea' ),
				'alegria_archive_newsletter_button_text' => array( __( 'Knop: tekst', 'alegria' ), 'text' ),
				'alegria_archive_newsletter_button_url'  => array( __( 'Knop: link', 'alegria' ), 'link' ),
			),
		),
		'alegria_archive_event'      => array(
			__( 'Archief: evenementen', 'alegria' ),
			__( 'De kop boven de evenementen (/evenement/). Zonder titel wordt "Evenementen" getoond; lege tekst en een knop zonder tekst of link worden weggelaten.', 'alegria' ) . ' ' . $link_hint,
			array(
				'alegria_archive_event_title'       => array( __( 'Titel', 'alegria' ), 'text' ),
				'alegria_archive_event_text'        => array( __( 'Tekst', 'alegria' ), 'textarea' ),
				'alegria_archive_event_button_text' => array( __( 'Knop: tekst', 'alegria' ), 'text' ),
				'alegria_archive_event_button_url'  => array( __( 'Knop: link', 'alegria' ), 'link' ),
			),
		),
		'alegria_newsletter_popup'   => array(
			__( 'Nieuwsbrief-popup', 'alegria' ),
			__( 'De popup met het inschrijfformulier (Contact Form 7, "Nieuwsbrief"). Elke link naar #nieuwsbrief-popup opent hem, op elke pagina.', 'alegria' ),
			array(
				'alegria_popup_title' => array( __( 'Titel', 'alegria' ), 'text' ),
				'alegria_popup_text'  => array( __( 'Tekst', 'alegria' ), 'textarea' ),
			),
		),
	);
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ): void {
		$sanitizers = array(
			'textarea' => 'sanitize_textarea_field',
			'url'      => 'esc_url_raw',
			'link'     => 'esc_url_raw',
			'text'     => 'sanitize_text_field',
		);

		$priority = 120;
		foreach ( alegria_customizer_sections() as $section => list( $title, $description, $settings ) ) {
			$wp_customize->add_section(
				$section,
				array(
					'title'       => $title,
					'description' => $description,
					'priority'    => $priority++,
				)
			);

			foreach ( $settings as $id => list( $label, $type ) ) {
				$wp_customize->add_setting(
					$id,
					array(
						'default'           => '',
						'sanitize_callback' => $sanitizers[ $type ],
					)
				);

				$wp_customize->add_control(
					$id,
					array(
						'label'   => $label,
						'section' => $section,
						'type'    => 'link' === $type ? 'text' : $type,
					)
				);
			}
		}
	}
);

/**
 * A Customizer setting's value, trimmed.
 *
 * @param string $id Setting ID from alegria_customizer_sections().
 * @return string
 */
function alegria_setting( string $id ): string {
	return trim( (string) get_theme_mod( $id, '' ) );
}
