<?php
/**
 * Page not found.
 *
 * @package alegria
 */

get_header();
?>
	<main id="primary" class="site-main">
		<section class="error">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Pagina niet gevonden', 'wp-rig' ); ?></h1>
			</header>

			<div class="page-content">
				<p><?php esc_html_e( 'De door u opgevraagde pagina kon niet worden gevonden. Controleer het webadres of probeer het later opnieuw.', 'wp-rig' ); ?></p>
			</div>
		</section>
	</main>
<?php
get_footer();
