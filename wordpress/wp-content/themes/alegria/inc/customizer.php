<?php
/**
 * Customizer settings for the footer: intro text, social media links and the call to action.
 *
 * Appearance → Customize → Footer. Empty fields are left out of the footer.
 *
 * @package alegria
 */

declare( strict_types=1 );

/**
 * The footer settings, as setting ID => array( label, type ).
 *
 * @return array<string, array{0: string, 1: string}>
 */
function alegria_footer_settings(): array {
	return array(
		'alegria_footer_text'      => array( __( 'Introtekst', 'alegria' ), 'textarea' ),
		'alegria_social_instagram' => array( __( 'Instagram-URL', 'alegria' ), 'url' ),
		'alegria_social_facebook'  => array( __( 'Facebook-URL', 'alegria' ), 'url' ),
		'alegria_social_linkedin'  => array( __( 'LinkedIn-URL', 'alegria' ), 'url' ),
		'alegria_footer_cta_text'  => array( __( 'Knop: tekst', 'alegria' ), 'text' ),
		'alegria_footer_cta_url'   => array( __( 'Knop: URL', 'alegria' ), 'url' ),
		'alegria_footer_link_text' => array( __( 'Tekstlink: tekst', 'alegria' ), 'text' ),
		'alegria_footer_link_url'  => array( __( 'Tekstlink: URL', 'alegria' ), 'url' ),
	);
}

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_section(
			'alegria_footer',
			array(
				'title'       => __( 'Footer', 'alegria' ),
				'description' => __( 'Lege velden worden niet getoond. De menukolommen stel je in onder Menu\'s (Footer: over ons, projecten en contact); de kolomtitel is de naam van het menu.', 'alegria' ),
				'priority'    => 120,
			)
		);

		$sanitizers = array(
			'textarea' => 'sanitize_textarea_field',
			'url'      => 'esc_url_raw',
			'text'     => 'sanitize_text_field',
		);

		foreach ( alegria_footer_settings() as $id => list( $label, $type ) ) {
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
					'section' => 'alegria_footer',
					'type'    => $type,
				)
			);
		}
	}
);

/**
 * A footer setting's value, trimmed.
 *
 * @param string $id Setting ID from alegria_footer_settings().
 * @return string
 */
function alegria_footer_setting( string $id ): string {
	return trim( (string) get_theme_mod( $id, '' ) );
}
