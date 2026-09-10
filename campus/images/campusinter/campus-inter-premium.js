/* CAMPUS INTER — compléments ergonomiques partagés */
(function () {
  'use strict';

  function initialise(page) {
    if (!page || page.dataset.ciPremiumReady) return;
    page.dataset.ciPremiumReady = 'true';

    var firstContent = page.querySelector('main, [role="main"], .ci-hero, section');
    if (firstContent && !page.querySelector('.ci-skip-link')) {
      if (!firstContent.id) firstContent.id = 'ci-contenu-principal';
      var skip = document.createElement('a');
      skip.className = 'ci-skip-link';
      skip.href = '#' + firstContent.id;
      skip.textContent = 'Aller au contenu';
      page.insertBefore(skip, page.firstChild);
    }

    page.querySelectorAll('img').forEach(function (image) {
      image.decoding = 'async';
    });

    page.querySelectorAll('.ci-filter, .ci-btn--filtre').forEach(function (button) {
      button.setAttribute('aria-pressed', button.classList.contains('ci-active') ? 'true' : 'false');
      button.addEventListener('click', function () {
        window.setTimeout(function () {
          page.querySelectorAll('.ci-filter, .ci-btn--filtre').forEach(function (item) {
            item.setAttribute('aria-pressed', item.classList.contains('ci-active') ? 'true' : 'false');
          });
        }, 0);
      });
    });

    page.querySelectorAll('.ci-faq-item').forEach(function (item, index) {
      var question = item.querySelector('.ci-faq-q');
      var answer = item.querySelector('.ci-faq-a');
      if (!question || !answer) return;
      if (!answer.id) answer.id = 'ci-faq-answer-' + index;
      question.setAttribute('aria-controls', answer.id);
      if (!question.hasAttribute('aria-expanded')) question.setAttribute('aria-expanded', 'false');
    });

    page.querySelectorAll('form').forEach(function (form) {
      form.querySelectorAll('input[type="email"]').forEach(function (input) { input.autocomplete = 'email'; });
      form.querySelectorAll('input[type="tel"]').forEach(function (input) { input.autocomplete = 'tel'; input.inputMode = 'tel'; });
      form.addEventListener('submit', function () {
        if (form.checkValidity()) form.setAttribute('aria-busy', 'true');
      });
      var status = form.querySelector('.ci-form-status');
      if (status && 'MutationObserver' in window) {
        new MutationObserver(function () {
          if (!status.hidden) form.removeAttribute('aria-busy');
        }).observe(status, { attributes: true, attributeFilter: ['hidden', 'class'], childList: true });
      }
    });
  }

  function boot() { document.querySelectorAll('.ci-page').forEach(initialise); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
}());
