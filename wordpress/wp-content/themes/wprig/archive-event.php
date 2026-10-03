<?php
/**
 * Event archive (/events).
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main archive-page archive-page--event">
		<div class="archive-block">
			<div class="archive-container">
				<header class="archive-header">
					<div>
						<h1 class="archive-title">Evenementen</h1>
						<p class="archive-description">
							<?php esc_html_e( 'Alle aankomende evenementen. Bekijk de details en schrijf je in.', 'wp-rig' ); ?>
						</p>
					</div>
				</header>

				<?php get_template_part( 'template-parts/archive/grid', null, array( 'empty_text' => __( 'Er zijn nog geen evenementen gepland.', 'wp-rig' ) ) ); ?>
			</div>
		</div>
	</main>
<?php
get_footer();
