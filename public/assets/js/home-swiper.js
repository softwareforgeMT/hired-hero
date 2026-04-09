// public/assets/js/home-swiper.js

document.addEventListener('DOMContentLoaded', function () {
    if (!window.Swiper) {
        return;
    }

    new Swiper('.targets-swiper', {
        loop: true,
        spaceBetween: 16,
        slidesPerView: 1.1,
        autoplay: {
            delay: 3500,            // 3.5 seconds per slide
            disableOnInteraction: false
        },
        breakpoints: {
            576: { slidesPerView: 2.1 },
            992: { slidesPerView: 3.1 }
        }
    });
});
