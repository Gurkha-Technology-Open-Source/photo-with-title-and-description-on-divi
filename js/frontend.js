(function($) {
    $(document).ready(function() {
        $('.ptd-achievements-showcase').each(function() {
            var $slider = $(this);
            var raw = $slider.attr('data-slider-settings');
            var sliderData = {};
            try {
                sliderData = raw ? JSON.parse(raw) : {};
            } catch (e) {
                sliderData = {};
            }

            var swiperOptions = {
                loop: true,
                grabCursor: true,

                // Navigation
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // Pagination
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                // Autoplay
                autoplay: sliderData.autoplay === 'on' ? {
                    delay: parseInt(sliderData.autoplay_speed || 3000, 10),
                    disableOnInteraction: false,
                } : false,
            };

            if (sliderData.show_arrows !== 'on') {
                swiperOptions.navigation = false;
            }

            if (sliderData.show_pagination !== 'on') {
                swiperOptions.pagination = false;
            }

            new Swiper($slider.find('.swiper-container')[0], swiperOptions);
        });
    });
})(jQuery);