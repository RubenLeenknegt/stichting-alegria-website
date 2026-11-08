import Swiper from 'swiper';
import { A11y, Autoplay, Keyboard, Navigation, Pagination } from 'swiper/modules';

Swiper.use( [ A11y, Autoplay, Keyboard, Navigation, Pagination ] );

window.Swiper = Swiper;
