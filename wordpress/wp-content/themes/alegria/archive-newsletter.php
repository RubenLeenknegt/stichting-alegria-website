<?php
/**
 * Newsletter archive (/nieuwsbrief). The header comes from the Customizer (Archief: nieuwsbrieven).
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main archive-page archive-page--newsletter">
		<div class="archive-block">
			<div class="archive-container">
				<?php get_template_part( 'template-parts/archive/header', null, array( 'type' => 'newsletter' ) ); ?>

				<?php get_template_part( 'template-parts/archive/grid', null, array( 'empty_text' => __( 'Er zijn nog geen nieuwsbrieven gepubliceerd.', 'alegria' ) ) ); ?>
			</div>
		</div>
	</main>
<?php
get_footer();
