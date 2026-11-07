<?php
/**
 * Event archive (/evenement). The header comes from the Customizer (Archief: evenementen).
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main archive-page archive-page--event">
		<div class="archive-block">
			<div class="archive-container">
				<?php get_template_part( 'template-parts/archive/header', null, array( 'type' => 'event' ) ); ?>

				<?php get_template_part( 'template-parts/archive/grid', null, array( 'empty_text' => __( 'Er zijn nog geen evenementen gepland.', 'alegria' ) ) ); ?>
			</div>
		</div>
	</main>
<?php
get_footer();
