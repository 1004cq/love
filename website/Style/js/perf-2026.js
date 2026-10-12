/**
 * Lightweight front-end performance helpers for LGNewUI.
 * - Prefer native lazy decoding for images already using data-src/LazyLoad
 * - Soft-fail missing optional libs after PJAX
 * - Expose a tiny timing mark for debugging
 */
(function () {
  'use strict';

  function mark(name) {
    try {
      if (window.performance && performance.mark) performance.mark(name);
    } catch (e) { /* ignore */ }
  }

  function enhanceImages(root) {
    var scope = root || document;
    scope.querySelectorAll('img').forEach(function (img) {
      if (!img.hasAttribute('decoding')) img.setAttribute('decoding', 'async');
      if (!img.hasAttribute('loading') && !img.classList.contains('CarouselImage') && !img.classList.contains('aiv_touxiang')) {
        img.setAttribute('loading', 'lazy');
      }
    });
  }

  function safeInit() {
    mark('lg-perf-init');
    enhanceImages(document);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', safeInit);
  } else {
    safeInit();
  }

  if (window.jQuery) {
    jQuery(document).on('pjax:complete pjax:end', function () {
      enhanceImages(document.getElementById('pjax-container') || document);
    });
  }

  window.LGPerf = {
    enhanceImages: enhanceImages,
    mark: mark
  };
})();
