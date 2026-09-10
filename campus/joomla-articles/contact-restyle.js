/**
 * Campus Inter — Contact page restyle (JS only, no PHP, no HTML edit)
 * Greffe le design ci- par-dessus le composant com_contact natif Joomla.
 */
(function () {
  'use strict';

  /* === 1. Injecter le CSS === */
  var s = document.createElement('style');
  s.textContent = [
    /* --- Reset section com_contact --- */
    '.com-contact{background:#f7f8fa!important;padding:0!important;margin:0!important}',
    '.com-contact .page-header{display:none!important}',
    '.com-contact .com-contact__container{display:none!important}',

    /* --- Section globale --- */
    '#cs-1528971561366{background:#f7f8fa!important;padding:80px 0 60px!important}',
    '#cs-1528971561366 .container{max-width:1100px!important}',

    /* --- Layout deux colonnes --- */
    '#cs-1528971561366 .astroid-row{display:grid!important;grid-template-columns:1fr 1fr!important;gap:40px!important;align-items:start!important}',
    '@media(max-width:900px){#cs-1528971561366 .astroid-row{grid-template-columns:1fr!important;gap:32px!important}}',

    /* --- Colonne gauche (info) --- */
    '#cs-1528971561366 .astroid-column:first-child{order:1}',
    '#cs-1528971561366 .astroid-column:first-child .astroid-message{display:none!important}',
    '#cs-1528971561366 .astroid-column:first-child .astroid-component{display:none!important}',

    /* --- Colonne formulaire --- */
    '#cs-1528971561366 .astroid-column:last-child{order:2}',
    '#cs-1528971561366 .astroid-column:last-child .astroid-component-area{background:#fff!important;border-radius:16px!important;box-shadow:0 4px 24px rgba(0,0,0,.06)!important;border:1px solid #e8ecf0!important;padding:32px!important;margin:0!important}',

    /* --- Titre du formulaire --- */
    '#cs-1528971561366 .com-contact h2{display:none!important}',
    '#cs-1528971561366 .com-contact__thumbnail{display:none!important}',

    /* --- Formulaire --- */
    '#cs-1528971561366 .com-contact__form{background:transparent!important;border:none!important;padding:0!important;margin:0!important}',
    '#cs-1528971561366 #contact-form{background:transparent!important;border:none!important;padding:0!important;margin:0!important;box-shadow:none!important}',
    '#cs-1528971561366 fieldset{border:none!important;padding:0!important;margin:0!important}',
    '#cs-1528971561366 legend{font-size:1.15rem!important;font-weight:700!important;color:#1a1a2e!important;margin-bottom:20px!important;padding:0!important;border:none!important}',

    /* --- Champs --- */
    '#cs-1528971561366 .control-group{margin-bottom:16px!important}',
    '#cs-1528971561366 .control-label{margin-bottom:6px!important}',
    '#cs-1528971561366 .control-label label{font-size:.82rem!important;font-weight:600!important;color:#1a1a2e!important;text-transform:uppercase!important;letter-spacing:.03em!important;display:block!important;margin:0!important;padding:0!important}',
    '#cs-1528971561366 .control-label .star{display:none!important}',
    '#cs-1528971561366 .control-label .form-control-feedback{display:none!important}',
    '#cs-1528971561366 .controls{position:relative!important}',
    '#cs-1528971561366 input[type="text"],#cs-1528971561366 input[type="email"],#cs-1528971561366 textarea{width:100%!important;padding:12px 14px!important;border:1.5px solid #dde3da!important;border-radius:10px!important;font-size:.92rem!important;color:#1a1a2e!important;background:#fafcf9!important;transition:border-color .25s,box-shadow .25s!important;font-family:inherit!important;box-sizing:border-box!important;outline:none!important}',
    '#cs-1528971561366 input[type="text"]:focus,#cs-1528971561366 input[type="email"]:focus,#cs-1528971561366 textarea:focus{border-color:#159447!important;box-shadow:0 0 0 3px rgba(21,148,71,.12)!important;background:#fff!important}',
    '#cs-1528971561366 textarea{min-height:120px!important;resize:vertical!important}',
    '#cs-1528971561366 .field-spacer{display:none!important}',

    /* --- Bouton --- */
    '#cs-1528971561366 .btn.btn-primary{display:inline-flex!important;align-items:center!important;gap:8px!important;padding:12px 28px!important;background:linear-gradient(135deg,#159447,#1db85e)!important;color:#fff!important;border:none!important;border-radius:10px!important;font-size:.92rem!important;font-weight:700!important;cursor:pointer!important;transition:all .25s!important;margin-top:8px!important;box-shadow:0 4px 16px rgba(21,148,71,.25)!important}',
    '#cs-1528971561366 .btn.btn-primary:hover{transform:translateY(-2px)!important;box-shadow:0 6px 24px rgba(21,148,71,.35)!important}',
    '#cs-1528971561366 .btn.btn-primary:active{transform:translateY(0)!important}',

    /* --- Erreurs validation --- */
    '#cs-1528971561366 .invalid{border-color:#e74c3c!important}',
    '#cs-1528971561366 .has-danger input{border-color:#e74c3c!important}',
    '#cs-1528971561366 .form-control-feedback{color:#e74c3c!important;font-size:.78rem!important;margin-top:4px!important}',

    /* --- Hidden inputs --- */
    '#cs-1528971561366 input[type="hidden"]{display:none!important}'
  ].join('\n');
  document.head.appendChild(s);

  /* === 2. Construire la carte info (colonne gauche) === */
  var leftCol = document.querySelector('#cs-1528971561366 .astroid-column:first-child');
  if (!leftCol) return;

  var infoCard = document.createElement('div');
  infoCard.className = 'ci-contact-info';
  infoCard.innerHTML =
    '<span class="ci-kicker">Let\'s talk about your project</span>' +
    '<h2>Contact us</h2>' +
    '<p>A question about studying or moving abroad? Our team in Lomé is here to help. We reply within 24 hours.</p>' +
    '<ul class="ci-contact-list">' +
      '<li><i class="fa-solid fa-location-dot"></i><div><strong>Address</strong><span>Boulevard de l\'Oti, opposite Massalassi, Bè-Kpota, Lomé, Togo</span></div></li>' +
      '<li><i class="fa-solid fa-phone"></i><div><strong>Phone</strong><a href="tel:+22822702596">+228 22 70 25 96</a></div></li>' +
      '<li><i class="fa-brands fa-whatsapp"></i><div><strong>WhatsApp</strong><a href="https://wa.me/22897754000">+228 97 75 40 00</a></div></li>' +
      '<li><i class="fa-solid fa-envelope"></i><div><strong>Email</strong><a href="mailto:contact@campusinter.com">contact@campusinter.com</a></div></li>' +
      '<li><i class="fa-solid fa-clock"></i><div><strong>Hours</strong><span>Mon – Fri: 8 AM – 6 PM | Sat: 9 AM – 1 PM</span></div></li>' +
    '</ul>' +
    '<div class="ci-contact-social">' +
      '<a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>' +
      '<a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>' +
      '<a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>' +
      '<a href="https://wa.me/22897754000" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>' +
    '</div>';

  leftCol.appendChild(infoCard);

  /* === 3. Injecter le CSS de la carte info === */
  var s2 = document.createElement('style');
  s2.textContent = [
    '.ci-contact-info{padding:32px;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.06);border:1px solid #e8ecf0}',
    '.ci-contact-info .ci-kicker{display:inline-block;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#159447;margin-bottom:10px}',
    '.ci-contact-info h2{margin:0 0 12px;font-size:1.6rem;font-weight:800;color:#1a1a2e;line-height:1.25}',
    '.ci-contact-info>p{color:#4a4a5a;line-height:1.65;margin:0 0 24px;font-size:.95rem}',
    '.ci-contact-list{list-style:none;padding:0;margin:0 0 24px}',
    '.ci-contact-list li{display:flex;align-items:flex-start;gap:14px;padding:14px 0;border-bottom:1px solid #f0f2f5}',
    '.ci-contact-list li:last-child{border-bottom:none}',
    '.ci-contact-list li i{flex-shrink:0;width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:rgba(21,148,71,.08);color:#159447;border-radius:10px;font-size:1rem;margin-top:2px}',
    '.ci-contact-list li div{display:flex;flex-direction:column;gap:2px}',
    '.ci-contact-list li strong{font-size:.82rem;font-weight:700;color:#1a1a2e;text-transform:uppercase;letter-spacing:.04em}',
    '.ci-contact-list li span,.ci-contact-list li a{font-size:.92rem;color:#4a4a5a;text-decoration:none;line-height:1.5}',
    '.ci-contact-list li a:hover{color:#159447}',
    '.ci-contact-social{display:flex;gap:10px}',
    '.ci-contact-social a{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:#f0f7f2;color:#159447;border-radius:10px;font-size:1rem;transition:all .25s}',
    '.ci-contact-social a:hover{background:#159447;color:#fff;transform:translateY(-2px)}'
  ].join('\n');
  document.head.appendChild(s2);

  /* === 4. Ajouter le titre dans le formulaire === */
  var fieldset = document.querySelector('#cs-1528971561366 fieldset');
  if (fieldset) {
    var h3 = document.createElement('h3');
    h3.textContent = 'Send us a message';
    h3.style.cssText = 'margin:0 0 20px;font-size:1.15rem;font-weight:700;color:#1a1a2e;';
    fieldset.insertBefore(h3, fieldset.firstChild);
  }

  /* === 5. WhatsApp flottant === */
  var wa = document.createElement('a');
  wa.href = 'https://wa.me/22897754000';
  wa.target = '_blank';
  wa.rel = 'noopener';
  wa.setAttribute('aria-label', 'Chat on WhatsApp');
  wa.className = 'ci-wa-float';
  wa.innerHTML = '<i class="fa-brands fa-whatsapp"></i>';
  document.body.appendChild(wa);

  /* === 6. Bouton retour en haut === */
  var btt = document.createElement('a');
  btt.href = '#';
  btt.setAttribute('aria-label', 'Back to top');
  btt.className = 'ci-back-top';
  btt.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
  document.body.appendChild(btt);

  /* === 7. CSS WhatsApp + retour en haut === */
  var s3 = document.createElement('style');
  s3.textContent = [
    '.ci-wa-float{position:fixed;bottom:24px;right:24px;z-index:9999;width:56px;height:56px;display:flex;align-items:center;justify-content:center;background:#25d366;color:#fff;border-radius:50%;font-size:1.6rem;box-shadow:0 4px 20px rgba(37,211,102,.4);transition:all .3s;text-decoration:none}',
    '.ci-wa-float:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(37,211,102,.55)}',
    '.ci-back-top{position:fixed;bottom:90px;right:28px;z-index:9999;width:44px;height:44px;display:none;align-items:center;justify-content:center;background:#fff;color:#159447;border:1.5px solid #dde3da;border-radius:50%;font-size:1rem;box-shadow:0 2px 12px rgba(0,0,0,.08);transition:all .3s;text-decoration:none}',
    '.ci-back-top:hover{background:#159447;color:#fff;border-color:#159447;transform:translateY(-2px)}',
    '.ci-back-top.visible{display:flex!important}'
  ].join('\n');
  document.head.appendChild(s3);

  /* === 8. Afficher/masquer le bouton retour en haut === */
  window.addEventListener('scroll', function () {
    if (window.scrollY > 400) {
      btt.classList.add('visible');
    } else {
      btt.classList.remove('visible');
    }
  });

})();
