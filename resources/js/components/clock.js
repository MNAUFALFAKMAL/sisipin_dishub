export function initClock() {
  const el = document.getElementById('topbar-clock');
  if (!el) return;

  const pad = n => String(n).padStart(2, '0');

  function update() {
    const now = new Date();
    el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())} WIB`;
  }

  update();
  setInterval(update, 30000);
}
