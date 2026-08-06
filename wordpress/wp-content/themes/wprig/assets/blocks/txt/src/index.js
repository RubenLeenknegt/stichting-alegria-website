// Use WP globals
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { registerBlockType } = (wp).blocks;
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { __ } = (wp).i18n;
import Edit from './edit';

registerBlockType('wp-rig/txt', {
	apiVersion: 2,
	title: __('Text', 'wp-rig'),
	category: 'text',
	description: __('A centered text block with an optional title (H2 or H3).', 'wp-rig'),
	attributes: {
		title: { type: 'string', default: '' },
		titleTag: { type: 'string', default: 'h2' },
		bodyText: { type: 'string', default: '' },
	},
	supports: {
		html: false,
	},
	edit: Edit,
	save() {
		return null; // Dynamic block, rendered by render.php
	},
});
