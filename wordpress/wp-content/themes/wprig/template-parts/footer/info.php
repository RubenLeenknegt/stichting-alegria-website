<?php
/**
 * Template part for displaying the footer info
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

$facebook_logo_svg  = wp_rig()->get_facebook_logo_svg();
$instagram_logo_svg = wp_rig()->get_instagram_logo_svg();
$linkedin_logo_svg  = wp_rig()->get_linkedin_logo_svg();
$privacy_policy_url = home_url( '/privacy-policy/' );
$cookie_policy_url  = home_url( '/cookie-policy/' );

?>

<div class="site-info">
	<div class="top">
		<div class="legal">
			<a href="<?php echo esc_url( $privacy_policy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'wprig' ); ?></a>
			<a href="<?php echo esc_url( $cookie_policy_url ); ?>"><?php esc_html_e( 'Cookie Policy', 'wprig' ); ?></a>
		</div>

		<ul class="social" aria-label="Social media links">
			<li>
				<a class="social-link" href="https://example.com/instagram" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
					<?php if ( ! empty( $instagram_logo_svg ) ) : ?>
						<?php echo $instagram_logo_svg; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
					<?php else : ?>
						<span aria-hidden="true">IG</span>
					<?php endif; ?>
				</a>
			</li>
			<li>
				<a class="social-link" href="https://example.com/facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
					<?php if ( ! empty( $facebook_logo_svg ) ) : ?>
						<?php echo $facebook_logo_svg; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
					<?php else : ?>
						<span aria-hidden="true">FB</span>
					<?php endif; ?>
				</a>
			</li>
			<li>
				<a class="social-link" href="https://example.com/linkedin" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
					<?php if ( ! empty( $linkedin_logo_svg ) ) : ?>
						<?php echo $linkedin_logo_svg; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
					<?php else : ?>
						<span aria-hidden="true">IN</span>
					<?php endif; ?>
				</a>
			</li>
		</ul>
	</div>

	<div class="bottom">
		<p class="copyright">&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> Stichting Alegria</p>
		<a class="link" href="https://www.linkedin.com/in/ruben-leenknegt-94a67a199/">Door: Ruben Leenknegt</a>
	</div>
</div><!-- .site-info -->
