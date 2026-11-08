<?php
/**
 * The social links from the Customizer (Footer section); empty ones are left out.
 * Icons: Font Awesome Free brand icons (CC BY 4.0).
 *
 * @var array $args { @type string $prefix Prefix of the list and link classes. }
 */

$socials = array_filter(
	array(
		'instagram'   => array( 'Instagram', alegria_setting( 'alegria_social_instagram' ) ),
		'facebook-f'  => array( 'Facebook', alegria_setting( 'alegria_social_facebook' ) ),
		'linkedin-in' => array( 'LinkedIn', alegria_setting( 'alegria_social_linkedin' ) ),
	),
	static fn( array $social ): bool => '' !== $social[1]
);

if ( ! $socials ) {
	return;
}
?>
<ul class="<?php echo esc_attr( $args['prefix'] ); ?>-social">
	<?php foreach ( $socials as $icon => list( $name, $url ) ) : ?>
		<li>
			<a class="<?php echo esc_attr( $args['prefix'] ); ?>-social-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
				<?php echo alegria_svg( $icon ); ?>
				<span class="screen-reader-text"><?php echo esc_html( sprintf( __( '%s (opent in nieuw venster)', 'alegria' ), $name ) ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
