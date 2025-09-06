(function($) {
    function initSlider($root) {
        if ($root.data('ptd-swiper-initialized')) return; // avoid double init

        var raw = $root.attr('data-slider-settings');
        var data = {};
        try {
            data = raw ? JSON.parse(raw) : {};
        } catch (e) {
            data = {};
        }

        var containerEl = $root.find('.swiper-container')[0];
        if (!containerEl) return;

        // If Swiper isn't loaded yet, retry shortly
        if (typeof Swiper === 'undefined') {
            setTimeout(function(){ initSlider($root); }, 50);
            return;
        }

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
            // 3-line preview is handled purely via CSS line-clamp
        };

        if (data.show_arrows !== 'on') {
            options.navigation = false;
        }

        if (data.show_pagination !== 'on') {
            options.pagination = false;
        }

    var sw = new Swiper(containerEl, options);
        $root.data('ptd-swiper-initialized', true);
    }

    function initAll() {
        $('.ptd-achievements-showcase').each(function() { initSlider($(this)); });
    }

    $(initAll);
    $(window).on('load', initAll);
    // No JS truncation required
})(jQuery);
