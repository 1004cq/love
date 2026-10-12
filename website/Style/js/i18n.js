/**
 * LGNewUI public site Chinese / English switcher.
 * Translates UI chrome via data-i18n* attributes and common phrase maps.
 * User-authored content (names, posts, custom titles) is left unchanged.
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'lg-lang';
  var dict = {
    zh: {
      nav_articles: '文章',
      nav_messages: '留言',
      nav_album: '相册',
      nav_list: '清单',
      latest_messages: '最新留言',
      article_list: '文章列表',
      love_day: 'Love Day',
      day: '天',
      hour: '时',
      minute: '分',
      second: '秒',
      messages_count_prefix: '祝福留言共',
      messages_count_mid: '条 当前显示',
      messages_count_suffix: '条',
      submit_message: '提交留言',
      submitting: '留言提交中...',
      submit_ok: '留言成功',
      submit_fail: '留言失败！',
      placeholder_qq: '请输入你的QQ',
      placeholder_nickname: '昵称',
      placeholder_message: '请输入留言内容...',
      placeholder_search: '请输入需查找的事件关键词',
      anonymous: '匿名',
      random_quote: '随机一言',
      query: '查询',
      done: '已完成',
      all: '全部',
      pending: '未完成',
      empty_title: '当前页面暂无数据',
      empty_slogan: '风雨同舟数十载 相濡以沫共白头',
      back_home: '返回首页',
      about_us: '关于我们',
      lang_label: '语言',
      switch_to_en: 'English',
      switch_to_zh: '中文'
    },
    en: {
      nav_articles: 'Diary',
      nav_messages: 'Notes',
      nav_album: 'Album',
      nav_list: 'List',
      latest_messages: 'Latest notes',
      article_list: 'Diary',
      love_day: 'Love Day',
      day: 'd',
      hour: 'h',
      minute: 'm',
      second: 's',
      messages_count_prefix: '',
      messages_count_mid: ' notes · showing ',
      messages_count_suffix: '',
      submit_message: 'Send note',
      submitting: 'Sending...',
      submit_ok: 'Sent',
      submit_fail: 'Failed',
      placeholder_qq: 'Your QQ number',
      placeholder_nickname: 'Nickname',
      placeholder_message: 'Write a blessing...',
      placeholder_search: 'Search events...',
      anonymous: 'Anon',
      random_quote: 'Random line',
      query: 'Search',
      done: 'Done',
      all: 'All',
      pending: 'Todo',
      empty_title: 'Nothing here yet',
      empty_slogan: 'Hand in hand, year after year',
      back_home: 'Back home',
      about_us: 'About us',
      lang_label: 'Language',
      switch_to_en: 'English',
      switch_to_zh: '中文'
    }
  };

  function detectLang() {
    var saved = localStorage.getItem(STORAGE_KEY);
    if (saved === 'zh' || saved === 'en') return saved;
    var nav = (navigator.language || navigator.userLanguage || 'zh').toLowerCase();
    return nav.indexOf('zh') === 0 ? 'zh' : 'en';
  }

  function t(key, lang) {
    lang = lang || current;
    return (dict[lang] && dict[lang][key]) || (dict.zh && dict.zh[key]) || key;
  }

  var current = detectLang();

  function translateDynamicPhrases(root, lang) {
    var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: function (node) {
        if (!node.nodeValue || !node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
        var parent = node.parentElement;
        if (!parent) return NodeFilter.FILTER_REJECT;
        var tag = parent.tagName;
        if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'TEXTAREA' || tag === 'CODE' || tag === 'PRE') {
          return NodeFilter.FILTER_REJECT;
        }
        if (parent.closest('[data-i18n-skip], .lg-lang-switch, .lg-theme-toggle')) {
          return NodeFilter.FILTER_REJECT;
        }
        // Skip user content areas
        if (parent.closest('.MsgText, .cont_text, .article_content, #md-view, .UserName, .name, .name_a, .list_info .name, .alogo, .img-male span, .img-female span, .nav_url, .title h1, .describe')) {
          return NodeFilter.FILTER_REJECT;
        }
        return NodeFilter.FILTER_ACCEPT;
      }
    });

    var nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);

    nodes.forEach(function (node) {
      if (typeof node._lgI18nSrc !== 'string') {
        node._lgI18nSrc = node.nodeValue;
      }
      var original = node._lgI18nSrc;
      var text = original;
      if (lang === 'en') {
        text = text
          .replace(/过了\s*(\d+)\s*天/g, 'Passed $1 days')
          .replace(/还有\s*(\d+)\s*天/g, '$1 days left')
          .replace(/祝福留言共/g, '')
          .replace(/条 当前显示/g, ' notes · showing ')
          .replace(/当前显示/g, 'showing ')
          .replace(/条(?![a-zA-Z])/g, '')
          .replace(/最新留言/g, t('latest_messages', 'en'))
          .replace(/文章列表/g, t('article_list', 'en'))
          .replace(/当前页面暂无数据/g, t('empty_title', 'en'))
          .replace(/风雨同舟数十载 相濡以沫共白头/g, t('empty_slogan', 'en'))
          .replace(/返回首页/g, t('back_home', 'en'))
          .replace(/提交留言/g, t('submit_message', 'en'))
          .replace(/留言提交中\.\.\./g, t('submitting', 'en'))
          .replace(/留言成功/g, t('submit_ok', 'en'))
          .replace(/留言失败！/g, t('submit_fail', 'en'))
          .replace(/随机一言/g, t('random_quote', 'en'))
          .replace(/已完成/g, t('done', 'en'))
          .replace(/未完成/g, t('pending', 'en'))
          .replace(/全部/g, t('all', 'en'))
          .replace(/查询/g, t('query', 'en'))
          .replace(/关于我们/g, t('about_us', 'en'))
          .replace(/(^|\s)文章(\s|$)/g, '$1' + t('nav_articles', 'en') + '$2')
          .replace(/(^|\s)留言(\s|$)/g, '$1' + t('nav_messages', 'en') + '$2')
          .replace(/(^|\s)相册(\s|$)/g, '$1' + t('nav_album', 'en') + '$2')
          .replace(/(^|\s)清单(\s|$)/g, '$1' + t('nav_list', 'en') + '$2')
          .replace(/匿名/g, t('anonymous', 'en'));
      }
      node.nodeValue = text;
    });
  }

  function applyAttributes(root, lang) {
    root.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (!key) return;
      if (!el.getAttribute('data-i18n-src')) {
        el.setAttribute('data-i18n-src', el.textContent.trim());
      }
      el.textContent = t(key, lang);
    });

    root.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-placeholder');
      if (!el.getAttribute('data-i18n-ph-src')) {
        el.setAttribute('data-i18n-ph-src', el.getAttribute('placeholder') || '');
      }
      el.setAttribute('placeholder', t(key, lang));
    });

    root.querySelectorAll('[data-i18n-title]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-title');
      el.setAttribute('title', t(key, lang));
    });

    root.querySelectorAll('[data-on-label]').forEach(function (el) {
      if (el.getAttribute('data-on-label') === '匿名' || el.getAttribute('data-i18n-on') === 'anonymous') {
        el.setAttribute('data-on-label', t('anonymous', lang));
        el.setAttribute('data-i18n-on', 'anonymous');
      }
    });
  }

  function syncSwitcher() {
    document.querySelectorAll('.lg-lang-switch [data-lang]').forEach(function (btn) {
      var active = btn.getAttribute('data-lang') === current;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  }

  function apply(lang) {
    current = lang === 'en' ? 'en' : 'zh';
    localStorage.setItem(STORAGE_KEY, current);
    document.documentElement.setAttribute('lang', current === 'zh' ? 'zh-CN' : 'en');
    document.documentElement.setAttribute('data-lg-lang', current);

    applyAttributes(document, current);
    translateDynamicPhrases(document.body || document.documentElement, current);
    syncSwitcher();

    // document.title is outside body; keep a zh source once
    if (!document.documentElement.getAttribute('data-i18n-title-src')) {
      document.documentElement.setAttribute('data-i18n-title-src', document.title || '');
    }
    var titleSrc = document.documentElement.getAttribute('data-i18n-title-src') || '';
    if (current === 'en') {
      document.title = titleSrc.replace(/关于我们/g, t('about_us', 'en'));
    } else {
      document.title = titleSrc;
    }

    try {
      document.dispatchEvent(new CustomEvent('lg:langchange', { detail: { lang: current } }));
    } catch (e) { /* ignore */ }
  }

  function ensureSwitcher() {
    if (document.querySelector('.lg-lang-switch')) return;
    var wrap = document.createElement('div');
    wrap.className = 'lg-lang-switch';
    wrap.setAttribute('role', 'group');
    wrap.setAttribute('aria-label', 'Language');
    wrap.innerHTML =
      '<button type="button" data-lang="zh" title="中文">中</button>' +
      '<button type="button" data-lang="en" title="English">EN</button>';
    document.body.appendChild(wrap);
    wrap.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-lang]');
      if (!btn) return;
      apply(btn.getAttribute('data-lang'));
    });
  }

  function boot() {
    ensureSwitcher();
    apply(current);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  if (window.jQuery) {
    jQuery(document).on('pjax:complete pjax:end', function () {
      apply(current);
    });
  } else {
    document.addEventListener('pjax:complete', function () {
      apply(current);
    });
  }

  window.LGI18n = {
    apply: apply,
    t: t,
    getLang: function () {
      return current;
    }
  };
})();
