/* K&S Fyzio – drobné vylepšenia rozhrania (bez závislostí) */
import { initQuiz } from './quiz.js';
import { initCompare } from './compare.js';
import { initAgeCalc } from './agecalc.js';
import { solvePow } from './pow.js';

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];

/* ---------- Hlavička: tieň pri scrolle + mobilné menu ---------- */
function initHeader() {
  const header = $('[data-header]');
  const burger = $('[data-burger]');
  const nav = $('[data-nav]');
  if (!header) return;

  const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  if (!burger || !nav) return;
  const setOpen = (open) => {
    burger.setAttribute('aria-expanded', String(open));
    document.body.classList.toggle('nav-open', open);
  };
  burger.addEventListener('click', () => setOpen(burger.getAttribute('aria-expanded') !== 'true'));
  nav.addEventListener('click', (e) => { if (e.target.closest('a')) setOpen(false); });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.body.classList.contains('nav-open')) { setOpen(false); burger.focus(); }
  });
  window.matchMedia('(min-width: 992px)').addEventListener('change', (m) => { if (m.matches) setOpen(false); });
}

/* ---------- Odhalenie prvkov pri scrolle ---------- */
function initReveal() {
  const items = $$('[data-reveal]');
  if (!items.length || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const vh = window.innerHeight;
  const io = new IntersectionObserver((entries) => {
    entries.forEach((en) => {
      if (en.isIntersecting) {
        en.target.classList.add('is-in');
        io.unobserve(en.target);
      }
    });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

  items.forEach((el, i) => {
    // To, čo je už vo výseku pri načítaní, nechávame bez animácie (žiadne blikanie).
    if (el.getBoundingClientRect().top < vh) return;
    el.classList.add('reveal');
    el.style.setProperty('--reveal-delay', `${(i % 4) * 70}ms`);
    io.observe(el);
  });
}

/* ---------- Formuláre (AJAX s fallbackom na bežné odoslanie) ---------- */
const MESSAGES = {
  required: 'Vyplňte prosím toto pole.',
  email: 'Zadajte prosím platný e-mail.',
  consent: 'Pre odoslanie je potrebný súhlas so spracovaním údajov.',
};

function fieldError(input) {
  if (input.type === 'checkbox' && input.required && !input.checked) return MESSAGES.consent;
  if (input.required && !String(input.value).trim()) return MESSAGES.required;
  if (input.type === 'email' && input.value && !input.checkValidity()) return MESSAGES.email;
  return '';
}

function setError(input, message) {
  const wrap = input.closest('.field');
  if (!wrap) return;
  const out = $('.field__error', wrap);
  wrap.classList.toggle('has-error', Boolean(message));
  if (out) out.textContent = message || '';
  if (message) input.setAttribute('aria-invalid', 'true');
  else input.removeAttribute('aria-invalid');
}

function showSuccess(form) {
  const card = form.closest('.form-card');
  const title = form.dataset.successTitle || 'Ďakujeme, správa bola odoslaná!';
  const text = form.dataset.successText || 'Čoskoro sa vám ozveme.';
  const box = document.createElement('div');
  box.className = 'form-success';
  box.setAttribute('role', 'status');
  box.tabIndex = -1;
  box.innerHTML = '<span class="form-success__icon"><svg class="icon" aria-hidden="true"><use href="#i-check"/></svg></span><h2></h2><p></p>';
  $('h2', box).textContent = title;
  $('p', box).textContent = text;
  (card || form.parentNode).replaceChildren(box);
  box.scrollIntoView({ behavior: 'smooth', block: 'center' });
  box.focus({ preventScroll: true });
}

function initForms() {
  $$('form[data-ajax-form]').forEach((form) => {
    const alertBox = $('.form-alert', form);
    const submit = $('[data-submit]', form);
    const controls = () => $$('input:not([type=hidden]):not([name=website]), select, textarea', form);

    // Proof-of-work: výpočet sa spustí hneď po načítaní, kým používateľ vypĺňa formulár.
    const powInput = $('input[name=pow]', form);
    const powBits = Number(powInput?.dataset.powBits) || 15;
    let powTask = null;
    const startPow = (salt) => {
      if (!powInput || !salt) return;
      powInput.value = '';
      powTask = solvePow(salt, powBits).then((n) => { powInput.value = n; }).catch(() => {});
    };
    startPow(powInput?.dataset.powSalt);

    const showAlert = (msg) => {
      if (!alertBox) return;
      alertBox.textContent = msg || '';
      alertBox.hidden = !msg;
    };

    controls().forEach((c) => {
      c.addEventListener('blur', () => { if (c.value || c.type === 'checkbox') setError(c, fieldError(c)); });
      c.addEventListener('input', () => { if (c.closest('.has-error')) setError(c, fieldError(c)); });
      c.addEventListener('change', () => { if (c.closest('.has-error')) setError(c, fieldError(c)); });
    });

    form.addEventListener('submit', async (e) => {
      showAlert('');
      let firstBad = null;
      controls().forEach((c) => {
        const msg = fieldError(c);
        setError(c, msg);
        if (msg && !firstBad) firstBad = c;
      });
      if (firstBad) {
        e.preventDefault();
        firstBad.focus();
        return;
      }
      if (!window.fetch || !window.FormData) return; // klasické odoslanie

      e.preventDefault();
      form.classList.add('is-sending');
      submit && (submit.disabled = true);

      try {
        if (powTask) await powTask; // overenie „nie som robot“ beží na pozadí od načítania stránky
        const res = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { Accept: 'application/json', 'X-Requested-With': 'fetch' },
          credentials: 'same-origin',
        });
        const data = await res.json();
        const csrf = $('input[name=csrf]', form);
        if (csrf && data.csrf) csrf.value = data.csrf;
        if (data.pow) startPow(data.pow); // soľ je jednorazová, pripravíme novú

        if (data.ok) {
          showSuccess(form);
          return;
        }
        let first = null;
        Object.entries(data.errors || {}).forEach(([name, msg]) => {
          const input = form.elements[name];
          if (input) { setError(input, msg); first = first || input; }
        });
        showAlert(data.error || (first ? 'Skontrolujte prosím označené polia.' : 'Odoslanie sa nepodarilo.'));
        (first || alertBox)?.focus?.();
      } catch (err) {
        showAlert('Odoslanie sa nepodarilo. Skontrolujte pripojenie alebo nám zavolajte.');
      } finally {
        form.classList.remove('is-sending');
        submit && (submit.disabled = false);
      }
    });
  });

  // Po klasickom odoslaní prenesieme focus na potvrdenie
  const ok = $('[data-success]');
  if (ok) ok.focus({ preventScroll: false });
}

function initDropdowns() {
  const subs = $$('[data-sub]');
  if (!subs.length) return;
  const closeAll = (except) => subs.forEach((s) => {
    if (s === except) return;
    s.classList.remove('is-open');
    $('.nav__toggle', s)?.setAttribute('aria-expanded', 'false');
  });
  subs.forEach((s) => {
    const btn = $('.nav__toggle', s);
    btn.addEventListener('click', () => {
      const open = !s.classList.contains('is-open');
      closeAll(s);
      s.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', String(open));
    });
  });
  document.addEventListener('click', (e) => { if (!e.target.closest('[data-sub]')) closeAll(); });
  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    const open = subs.find((s) => s.classList.contains('is-open'));
    if (open) { closeAll(); $('.nav__toggle', open).focus(); }
  });
}

function initToTop() {
  const btn = $('[data-to-top]');
  if (!btn) return;
  let shown = false;
  const update = () => {
    const show = window.scrollY > 600;
    if (show === shown) return;
    shown = show;
    if (show) { btn.hidden = false; requestAnimationFrame(() => btn.classList.add('is-visible')); }
    else { btn.classList.remove('is-visible'); setTimeout(() => { if (!shown) btn.hidden = true; }, 250); }
  };
  window.addEventListener('scroll', update, { passive: true });
  update();
  btn.addEventListener('click', () => {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
  });
}

/* ---------- Informácia o cookies: zobrazí sa, kým ju návštevník neodklikne ---------- */
function initCookieNotice() {
  const box = $('[data-cookie-notice]');
  if (!box) return;
  const KEY = 'ksf_cookie_consent';
  const get = () => { try { return localStorage.getItem(KEY); } catch (e) { return null; } };
  const set = (v) => { try { localStorage.setItem(KEY, v); } catch (e) { /* úložisko nedostupné */ } };

  // Stav súhlasu pre budúce voliteľné skripty (analytika a pod.): window.ksfConsent === 'accepted'
  window.ksfConsent = get();
  if (!window.ksfConsent) box.hidden = false;

  $$('[data-cookie-choice]', box).forEach((btn) => btn.addEventListener('click', () => {
    window.ksfConsent = btn.dataset.cookieChoice;
    set(window.ksfConsent);
    box.hidden = true;
    document.dispatchEvent(new CustomEvent('ksf:consent', { detail: window.ksfConsent }));
  }));

  // Odkazy „Nastavenia cookies“ znovu otvoria voľbu.
  $$('[data-cookie-reopen]').forEach((a) => a.addEventListener('click', (e) => {
    e.preventDefault();
    box.hidden = false;
    $('[data-cookie-choice]', box).focus();
  }));
}

initHeader();
initCookieNotice();
initDropdowns();
initToTop();
initReveal();
initForms();
initQuiz();
initCompare();
initAgeCalc();
