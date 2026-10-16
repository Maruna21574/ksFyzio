/* Administrácia – opakovateľné položky, fotky, potvrdenia. Bez závislostí. */
(function () {
  'use strict';
  var uid = Date.now();

  document.addEventListener('click', function (ev) {
    var t = ev.target.closest('button');
    if (!t) return;

    if (t.hasAttribute('data-confirm') && !t.hasAttribute('data-rep-del') && !window.confirm(t.getAttribute('data-confirm'))) {
      ev.preventDefault();
      return;
    }

    var rep = t.closest('[data-rep]');
    var item = t.closest('[data-rep-item]');

    if (t.hasAttribute('data-rep-add') && rep) {
      var depth = rep.getAttribute('data-depth');
      var tpl = rep.querySelector(':scope > [data-rep-tpl]');
      var html = tpl.innerHTML.split('__i' + depth + '__').join('n' + (uid++));
      var box = rep.querySelector(':scope > [data-rep-items]');
      box.insertAdjacentHTML('beforeend', html);
      var added = box.lastElementChild;
      var first = added.querySelector('input[type=text], textarea');
      if (first) first.focus();
      added.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else if (t.hasAttribute('data-rep-del') && item) {
      if (window.confirm(t.getAttribute('data-confirm'))) item.remove();
    } else if (t.hasAttribute('data-rep-up') && item && item.previousElementSibling) {
      item.parentNode.insertBefore(item, item.previousElementSibling);
    } else if (t.hasAttribute('data-rep-down') && item && item.nextElementSibling) {
      item.parentNode.insertBefore(item.nextElementSibling, item);
    } else if (t.hasAttribute('data-img-clear')) {
      var img = t.closest('[data-img]');
      img.querySelector('[data-img-val]').value = '';
      img.querySelector('[data-img-file]').value = '';
      var prev = img.querySelector('[data-img-prev]');
      prev.hidden = true;
      prev.removeAttribute('src');
    }
  });

  // Náhľad vybranej fotky (data: URL – povolené v CSP).
  document.addEventListener('change', function (ev) {
    var inp = ev.target;
    if (!inp.matches || !inp.matches('[data-img-file]') || !inp.files || !inp.files[0]) return;
    var f = inp.files[0];
    if (f.size > 12 * 1024 * 1024) {
      window.alert('Fotka je príliš veľká (najviac 12 MB).');
      inp.value = '';
      return;
    }
    var r = new FileReader();
    r.onload = function () {
      var prev = inp.closest('[data-img]').querySelector('[data-img-prev]');
      prev.src = r.result;
      prev.hidden = false;
    };
    r.readAsDataURL(f);
  });

  // Názov položky v hlavičke sa mení podľa prvého poľa.
  document.addEventListener('input', function (ev) {
    var inp = ev.target;
    var item = inp.closest && inp.closest('[data-rep-item]');
    if (!item || inp.tagName !== 'INPUT' || inp.type !== 'text') return;
    var body = item.querySelector(':scope > .rep__body');
    if (body && body.querySelector('input[type=text]') === inp) {
      var title = item.querySelector(':scope > .rep__bar [data-rep-title]');
      title.textContent = inp.value.trim().slice(0, 70) || 'Nová položka';
    }
  });

  // Upozornenie pri odchode s neuloženými zmenami.
  var form = document.querySelector('.aform');
  if (form) {
    var dirty = false;
    form.addEventListener('input', function () { dirty = true; });
    form.addEventListener('click', function (e) { if (e.target.closest('[data-rep-del],[data-rep-add],[data-rep-up],[data-rep-down],[data-img-clear]')) dirty = true; });
    form.addEventListener('submit', function () { dirty = false; });
    window.addEventListener('beforeunload', function (e) { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
  }
})();
