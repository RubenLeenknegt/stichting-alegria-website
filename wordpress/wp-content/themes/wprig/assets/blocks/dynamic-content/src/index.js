// Use WP globals
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { registerBlockType } = (wp).blocks;
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { __ } = (wp).i18n;
import Edit from './edit';

registerBlockType('wp-rig/embed-shortcode', {
	apiVersion: 2,
	title: __('Embed / Shortcode', 'wp-rig'),
	category: 'widgets',
	description: __('Outputs a WordPress shortcode, or embeds raw iframe/HTML markup, centered on the page.', 'wp-rig'),
	attributes: {
		content: { type: 'string', default: '' },
		isIframe: { type: 'boolean', default: false },
	},
	supports: {
		html: false,
		align: ['wide', 'full'],
	},
	edit: Edit,
	save() {
		return null; // Dynamic block, rendered by render.php
	},
});
