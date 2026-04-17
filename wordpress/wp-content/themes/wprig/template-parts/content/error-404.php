<?php
/**
 * Template part for displaying the page content when a 404 error has occurred
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>
<section class="error">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Pagina niet gevonden', 'wp-rig' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<p><?php esc_html_e( 'De door u opgevraagde pagina kon niet worden gevonden. Controleer het webadres of probeer het later opnieuw.', 'wp-rig' ); ?></p>
	</div><!-- .page-content -->
</section><!-- .error -->
