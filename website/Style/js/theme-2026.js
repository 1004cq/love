(function () {
  'use strict';
  var key = 'lg-theme';
  var root = document.documentElement;
  var saved = localStorage.getItem(key);
  var preferredDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

  function apply(theme) {
    root.setAttribute('data-lg-theme', theme);
    var button = document.querySelector('.lg-theme-toggle');
    if (button) {
      button.textContent = theme === 'dark' ? '☀' : '☾';
      button.setAttribute('aria-label', theme === 'dark' ? '切换到浅色模式' : '切换到深色模式');
    }
  }

  apply(saved || (preferredDark ? 'dark' : 'light'));
  document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.lg-theme-toggle')) return;
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'lg-theme-toggle';
    button.title = '切换主题';
    button.addEventListener('click', function () {
      var next = root.getAttribute('data-lg-theme') === 'dark' ? 'light' : 'dark';
      localStorage.setItem(key, next);
      apply(next);
    });
    document.body.appendChild(button);
    apply(root.getAttribute('data-lg-theme'));
  });
})();
