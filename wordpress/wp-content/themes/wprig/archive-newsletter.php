<?php
/**
 * Newsletter archive (/newsletters), with the Contact Form 7 sign-up form.
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main archive-page archive-page--newsletter">
		<div class="archive-block">
			<div class="archive-container">
				<header class="archive-header">
					<div>
						<h1 class="archive-title">Nieuwsbrief</h1>
						<p class="archive-description">
							<?php esc_html_e( 'Alle gepubliceerde nieuwsbrieven. Schrijf je in om nieuwe uitgaven rechtstreeks in je inbox te ontvangen.', 'wp-rig' ); ?>
						</p>
						<?php echo do_shortcode( '[contact-form-7 id="fdcb489" title="Nieuwsbrief"]' ); ?>
					</div>
				</header>

				<?php get_template_part( 'template-parts/archive/grid', null, array( 'empty_text' => __( 'Er zijn nog geen nieuwsbrieven gepubliceerd.', 'wp-rig' ) ) ); ?>
			</div>
		</div>
	</main>
<?php
get_footer();
