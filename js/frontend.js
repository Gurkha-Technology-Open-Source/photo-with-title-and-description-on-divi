(function($) {
    $(document).ready(function() {
        $('.ptd-achievements-showcase').each(function() {
            var $slider = $(this);
            var sliderData = $slider.data('slider-settings');

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
                    delay: sliderData.autoplay_speed,
                    disableOnInteraction: false,
                } : false,
            };

            if (sliderData.show_arrows !== 'on') {
                swiperOptions.navigation = false;
            }

            if (sliderData.show_pagination !== 'on') {
                swiperOptions.pagination = false;
            }

            new Swiper($slider.find('.swiper-container'), swiperOptions);
        });
    });
})(jQuery);