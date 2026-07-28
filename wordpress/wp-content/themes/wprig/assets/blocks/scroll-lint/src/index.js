// Use WP globals
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { registerBlockType } = (wp).blocks;
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { __ } = (wp).i18n;
import Edit from './edit';

registerBlockType('wp-rig/scrolllint', {
	apiVersion: 2,
	title: __('Scrolllint', 'wp-rig'),
	category: 'widgets',
	description: __('A full-width strip of images that scrolls continuously left to right.', 'wp-rig'),
	attributes: {
		title: { type: 'string', default: '' },
		images: { type: 'array', default: [], items: { type: 'object' } },
	},
	supports: {
		html: false,
		align: ['full'],
	},
	edit: Edit,
	save() {
		return null; // Dynamic block, rendered by render.php
	},
});
