// Use WP globals
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { registerBlockType } = (wp).blocks;
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { __ } = (wp).i18n;
import Edit from './edit';

registerBlockType('wp-rig/img', {
	apiVersion: 2,
	title: __('Image', 'wp-rig'),
	category: 'media',
	description: __('A simple centered single image.', 'wp-rig'),
	attributes: {
		imageUrl: { type: 'string', default: '' },
		imageId: { type: 'number', default: 0 },
		imageAlt: { type: 'string', default: '' },
	},
	supports: {
		html: false,
	},
	edit: Edit,
	save() {
		return null; // Dynamic block, rendered by render.php
	},
});
