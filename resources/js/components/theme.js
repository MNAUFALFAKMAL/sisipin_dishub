const STORAGE_KEY = 'theme';

export function initTheme() {
  const toggle = document.getElementById('system-theme-toggle');
  if (!toggle) return;

  const html = document.documentElement;
  const moonIcon = document.getElementById('system-moon-icon');
  const sunIcon = document.getElementById('system-sun-icon');

  function updateUI() {
    const isDark = html.classList.contains('dark');
    if (moonIcon) moonIcon.classList.toggle('hidden', isDark);
    if (sunIcon) sunIcon.classList.toggle('hidden', !isDark);
    toggle.style.color = isDark ? '#ffd000' : '#64748b';
  }

  toggle.addEventListener('click', () => {
    const next = html.classList.contains('dark') ? 'light' : 'dark';
    html.classList.toggle('dark', next === 'dark');
    localStorage.setItem(STORAGE_KEY, next);
    updateUI();
  });

  updateUI();

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem(STORAGE_KEY)) {
      html.classList.toggle('dark', e.matches);
      updateUI();
    }
  });
}
