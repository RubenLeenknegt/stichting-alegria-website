/**
 * Swiper, shared by the upcoming-events and scrolllint blocks.
 *
 * Built to assets/dist/swiper.js and registered as the `alegria-swiper` script in inc/assets.php.
 * Block view scripts `import Swiper from 'swiper'`; build.mjs points that import at the
 * window.Swiper global set here and makes `alegria-swiper` a dependency of the view script,
 * so WordPress loads Swiper once, and only on pages with one of those blocks.
 */
import Swiper from 'swiper';
import { A11y, Autoplay, Keyboard, Navigation, Pagination } from 'swiper/modules';

Swiper.use( [ A11y, Autoplay, Keyboard, Navigation, Pagination ] );

window.Swiper = Swiper;
