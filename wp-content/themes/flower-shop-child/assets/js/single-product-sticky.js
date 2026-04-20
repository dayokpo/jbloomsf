(function () {
    function initStickyProductGallery() {
        var wrapper = document.querySelector('.single-product .cmsmasters_single_product');

        if (!wrapper) {
            return;
        }

        var leftColumn = wrapper.querySelector('.cmsmasters_product_left_column');
        var rightColumn = wrapper.querySelector('.cmsmasters_product_right_column');
        var gallery = wrapper.querySelector('.cmsmasters_product_images');
        var desktopBreakpoint = 1024;
        var topOffset = 200;
        var ticking = false;

        if (!leftColumn || !rightColumn || !gallery) {
            return;
        }

        function resetGallery() {
            gallery.style.position = '';
            gallery.style.top = '';
            gallery.style.right = '';
            gallery.style.bottom = '';
            gallery.style.left = '';
            gallery.style.width = '';
            leftColumn.style.minHeight = '';
            gallery.classList.remove('flower-shop-sticky-active', 'flower-shop-sticky-bottom');
        }

        function updateGalleryPosition() {
            ticking = false;

            if (window.innerWidth < desktopBreakpoint) {
                resetGallery();
                return;
            }

            var galleryHeight = gallery.offsetHeight;
            var rightHeight = rightColumn.offsetHeight;
            var leftRect = leftColumn.getBoundingClientRect();
            var wrapperRect = wrapper.getBoundingClientRect();
            var wrapperTop = window.pageYOffset + wrapperRect.top;
            var wrapperBottom = wrapperTop + wrapper.offsetHeight;
            var startStickY = wrapperTop - topOffset;
            var stopStickY = wrapperBottom - galleryHeight - topOffset;

            if (!galleryHeight || !leftRect.width || rightHeight <= galleryHeight + topOffset) {
                resetGallery();
                return;
            }

            leftColumn.style.minHeight = Math.max(galleryHeight, rightHeight) + 'px';

            if (window.pageYOffset <= startStickY) {
                resetGallery();
                return;
            }

            if (window.pageYOffset >= stopStickY) {
                gallery.style.position = 'absolute';
                gallery.style.top = 'auto';
                gallery.style.right = '0';
                gallery.style.bottom = '0';
                gallery.style.left = '0';
                gallery.style.width = '100%';
                gallery.classList.add('flower-shop-sticky-active', 'flower-shop-sticky-bottom');
                return;
            }

            gallery.style.position = 'fixed';
            gallery.style.top = topOffset + 'px';
            gallery.style.right = 'auto';
            gallery.style.bottom = 'auto';
            gallery.style.left = leftRect.left + 'px';
            gallery.style.width = leftRect.width + 'px';
            gallery.classList.add('flower-shop-sticky-active');
            gallery.classList.remove('flower-shop-sticky-bottom');
        }

        function requestUpdate() {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(updateGalleryPosition);
        }

        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', requestUpdate);
        window.addEventListener('load', requestUpdate);

        document.addEventListener('click', function (event) {
            if (event.target.closest('.cmsmasters_product_thumb')) {
                window.requestAnimationFrame(requestUpdate);
            }
        });

        requestUpdate();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStickyProductGallery);
    } else {
        initStickyProductGallery();
    }
})();
