<?php
/**
 * Template part for displaying the mobile menu toggle button
 * Pure CSS hamburger - no SVG needed
 *
 * @package alegria
 */

?>
<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'alegria' ); ?>" aria-controls="primary-menu" aria-expanded="false">
	<span></span>
	<span class="menu-toggle-text"><?php esc_html_e( 'Menu', 'alegria' ); ?></span>
</button>
