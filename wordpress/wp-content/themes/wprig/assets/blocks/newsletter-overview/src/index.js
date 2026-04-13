// Use WP globals
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { registerBlockType } = (wp).blocks;
// eslint-disable-next-line @typescript-eslint/no-unsafe-member-access
const { __ } = (wp).i18n;
import Edit from './edit';

registerBlockType('wp-rig/newsletter-overview', {
	apiVersion: 3,
	title: __('Newsletter Overview', 'wp-rig'),
	edit: Edit,
	save() {
		return null;
	},
});
