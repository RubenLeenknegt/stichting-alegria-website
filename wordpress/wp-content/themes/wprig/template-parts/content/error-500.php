<?php
/**
 * Template part for displaying the page content when a 500 error has occurred
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>
<section class="error">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Er is een interne fout opgetreden', 'wp-rig' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<p><?php esc_html_e( 'Er is iets misgegaan aan onze kant. Probeer het over enkele minuten opnieuw.', 'wp-rig' ); ?></p>
	</div><!-- .page-content -->
</section><!-- .error -->
