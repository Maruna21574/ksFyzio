/* Posuvník pred / po */
export function initCompare() {
  document.querySelectorAll('[data-compare]').forEach((el) => {
    const range = el.querySelector('.compare__range');
    if (!range) return;
    const set = () => el.style.setProperty('--pos', `${range.value}%`);
    range.addEventListener('input', set);
    set();
  });
}
