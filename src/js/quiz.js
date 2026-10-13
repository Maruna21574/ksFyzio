/* Test pohybu – orientačné vyhodnotenie signálov */
export function initQuiz() {
  const form = document.querySelector('[data-quiz]');
  if (!form) return;
  const q = (s) => form.querySelector(s);
  const boxes = [...form.querySelectorAll('input[name=s]')];
  const bar = q('[data-quiz-bar]');
  const count = q('[data-quiz-count]');
  const title = q('[data-quiz-title]');
  const text = q('[data-quiz-text]');
  const book = q('[data-quiz-book]');
  const result = q('[data-quiz-result]');

  const levels = [
    { max: 0, cls: '', title: 'Označte, čo ste si všimli', text: 'Po označení signálov tu uvidíte odporúčanie.' },
    { max: 1, cls: 'is-low', title: 'Stojí za to to sledovať', text: 'Zatiaľ ide o jeden signál. Sledujte, či sa opakuje alebo zhoršuje. Ak vás niečo znepokojuje, radi vám poradíme.' },
    { max: 3, cls: 'is-mid', title: 'Odporúčame konzultáciu', text: 'Viacero signálov môže znamenať, že psovi pohyb nerobí dobre. Vyšetrenie pohybu vám povie viac a navrhne ďalší postup.' },
    { max: 99, cls: 'is-high', title: 'Neodkladajte vyšetrenie', text: 'Pes ukazuje viacero zmien naraz. Odporúčame ho dať vyšetriť a dohodnúť sa na ďalšom postupe čo najskôr.' },
  ];

  const update = () => {
    const picked = boxes.filter((b) => b.checked);
    const n = picked.length;
    const lvl = levels.find((l) => n <= l.max);
    result.className = `quiz__result ${lvl.cls}`.trim();
    bar.style.width = `${(n / boxes.length) * 100}%`;
    count.textContent = String(n);
    title.textContent = lvl.title;
    text.textContent = lvl.text;
    const base = form.dataset.base;
    book.href = n ? `${base}?signaly=${picked.map((b) => b.value).join(',')}` : base;
  };

  boxes.forEach((b) => b.addEventListener('change', update));
  update();
}
