(function($) {
    $(function() {
        $('.ptd-achievements-showcase').each(function() {
            var $root = $(this);
            var raw = $root.attr('data-slider-settings');
            var data = {};
            try {
                data = raw ? JSON.parse(raw) : {};
            } catch (e) {
                data = {};
            }

            var containerEl = $root.find('.swiper-container')[0];
            if (!containerEl || typeof Swiper === 'undefined') return;

            // Scope navigation/pagination to this slider only
            var nextEl = $root.find('.swiper-button-next')[0] || null;
            var prevEl = $root.find('.swiper-button-prev')[0] || null;
            var paginationEl = $root.find('.swiper-pagination')[0] || null;

            var options = {
                loop: true,
                grabCursor: true,
                watchOverflow: true,
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    }
                },
                navigation: (nextEl && prevEl) ? { nextEl: nextEl, prevEl: prevEl } : false,
                pagination: paginationEl ? { el: paginationEl, clickable: true } : false,
                autoplay: data.autoplay === 'on' ? {
                    delay: parseInt(data.autoplay_speed || 3000, 10),
                    disableOnInteraction: false,
                } : false,
            };

            if (data.show_arrows !== 'on') {
                options.navigation = false;
            }

            if (data.show_pagination !== 'on') {
                options.pagination = false;
            }

            new Swiper(containerEl, options);
        });
    });
})(jQuery);
