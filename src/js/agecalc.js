/* Kalkulačka veku psa – orientačný prepočet na ľudské roky */
export function initAgeCalc() {
  const root = document.querySelector('[data-agecalc]');
  if (!root) return;

  const input = root.querySelector('#age-years');
  const human = root.querySelector('[data-age-human]');
  const stage = root.querySelector('[data-age-stage]');
  const text = root.querySelector('[data-age-text]');
  const cta = root.querySelector('[data-age-cta]');

  // Pravidlo: 1. rok ≈ 15, 2. rok ≈ +9, každý ďalší rok podľa veľkosti.
  const perYear = { small: 4, medium: 5, large: 6 };
  const seniorFrom = { small: 10, medium: 8, large: 7 };

  const calc = (years, size) => {
    if (years <= 0) return 0;
    if (years <= 1) return years * 15;
    if (years <= 2) return 15 + (years - 1) * 9;
    return 24 + (years - 2) * perYear[size];
  };

  const update = () => {
    const years = parseFloat(String(input.value).replace(',', '.'));
    const size = root.querySelector('input[name=size]:checked').value;
    if (!Number.isFinite(years) || years < 0 || years > 30) {
      human.textContent = '–';
      stage.textContent = '';
      text.textContent = 'Zadajte vek psa v rokoch (0 až 30).';
      return;
    }
    human.textContent = String(Math.round(calc(years, size)));

    let label;
    let msg;
    let senior = false;
    if (years < 1) {
      label = 'Šteňa';
      msg = 'Šteniatko rýchlo rastie. Správny pohyb a zdravý vývoj kĺbov sú teraz dôležité.';
    } else if (years < 3) {
      label = 'Mladý dospelý pes';
      msg = 'Je plný energie. Dbajte na primeranú záťaž a dostatok regenerácie po športe.';
    } else if (years < seniorFrom[size]) {
      label = 'Dospelý pes';
      msg = 'Je v najlepších rokoch. Pravidelný pohyb a sledovanie drobných zmien v chôdzi pomáha predchádzať ťažkostiam.';
    } else {
      label = 'Senior';
      senior = true;
      msg = 'V tomto veku sa môže pohyb zhoršovať – ťažšie vstávanie, vyhýbanie sa schodom, kratšie prechádzky. Správna podpora môže výrazne zlepšiť komfort.';
    }
    stage.textContent = label;
    text.textContent = msg;
    if (cta) {
      const base = cta.dataset.base || cta.getAttribute('href').split('?')[0];
      cta.dataset.base = base;
      cta.href = senior ? `${base}?sluzba=podpora-pohybu-starsich-psov` : base;
    }
  };

  input.addEventListener('input', update);
  root.querySelectorAll('input[name=size]').forEach((r) => r.addEventListener('change', update));
  update();
}
