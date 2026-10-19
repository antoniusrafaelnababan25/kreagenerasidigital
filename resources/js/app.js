import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ═══════════════════════════════════════════════════════════
// SWIPER — Carousel Library
// ═══════════════════════════════════════════════════════════
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';

// Swiper CSS
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

// Expose ke window
window.Swiper = Swiper;

// Register modules
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);

// ═══════════════════════════════════════════════════════════
// AUTO INIT — Semua Swiper
// ═══════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    // Showcase (services — 1 per view)
    document.querySelectorAll('.swiper-showcase').forEach(function(wrapper) {
        const swiperEl = wrapper.querySelector('.swiper');
        if (!swiperEl) return;

        new Swiper(swiperEl, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: wrapper.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: wrapper.querySelector('.swiper-showcase-next'),
                prevEl: wrapper.querySelector('.swiper-showcase-prev'),
            },
        });
    });

    // Services Grid (multi-slide)
    document.querySelectorAll('.swiper-services').forEach(function(wrapper) {
        const swiperEl = wrapper.querySelector('.swiper');
        if (!swiperEl) return;

        new Swiper(swiperEl, {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: false,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: wrapper.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: wrapper.querySelector('.swiper-services-next'),
                prevEl: wrapper.querySelector('.swiper-services-prev'),
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 32 },
            },
        });
    });

    // Highlights
    document.querySelectorAll('.swiper-highlights').forEach(function(wrapper) {
        const swiperEl = wrapper.querySelector('.swiper');
        if (!swiperEl) return;

        new Swiper(swiperEl, {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: wrapper.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: wrapper.querySelector('.swiper-highlights-next'),
                prevEl: wrapper.querySelector('.swiper-highlights-prev'),
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 32 },
            },
        });
    });
});


    // Portfolio News Carousel
    document.querySelectorAll('.swiper-portfolio').forEach(function(wrapper) {
        const swiperEl = wrapper.querySelector('.swiper');
        if (!swiperEl) return;

        new Swiper(swiperEl, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: { crossFade: true },
            pagination: {
                el: wrapper.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
                nextEl: wrapper.querySelector('.swiper-portfolio-next'),
                prevEl: wrapper.querySelector('.swiper-portfolio-prev'),
            },
        });
    });