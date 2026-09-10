/* CAMPUS INTER — insertion du footer partagé */
(function () {
  'use strict';
  var footerUrl = '/footer.html';
  var cssUrl = '/footer.css';

  function loadCss() {
    if (document.querySelector('link[data-ci-footer-css]')) return;
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = cssUrl;
    link.setAttribute('data-ci-footer-css', 'true');
    document.head.appendChild(link);
  }

  function mountFooter(markup) {
    document.querySelectorAll('[data-ci-footer]').forEach(function (mount) {
      if (mount.tagName.toLowerCase() === 'footer') mount.outerHTML = markup;
      else mount.innerHTML = markup;
    });
  }

  function boot() {
    var mounts = document.querySelectorAll('[data-ci-footer]');
    if (!mounts.length) return;
    loadCss();
    fetch(footerUrl, { credentials: 'same-origin' })
      .then(function (response) { if (!response.ok) throw new Error('Footer unavailable'); return response.text(); })
      .then(mountFooter)
      .catch(function () { mountFooter('<footer class="ci-footer"><div class="ci-container"><p>© 2026 Campus Inter. Tous droits réservés.</p></div></footer>'); });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
}());
