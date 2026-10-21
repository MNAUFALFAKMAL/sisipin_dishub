export function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (!sidebar) return;

  const open = () => {
    sidebar.classList.add('sidebar--open');
    overlay?.classList.add('overlay--visible');
    document.body.style.overflow = 'hidden';
  };

  const close = () => {
    sidebar.classList.remove('sidebar--open');
    overlay?.classList.remove('overlay--visible');
    document.body.style.overflow = '';
  };

  document.querySelectorAll('[data-sidebar-toggle]').forEach(el => el.addEventListener('click', open));
  overlay?.addEventListener('click', close);

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') close();
  });
}

export function initUserDropdown() {
  const btn = document.getElementById('user-menu-button');
  const menu = document.getElementById('user-menu');
  if (!btn || !menu) return;

  btn.addEventListener('click', e => {
    e.stopPropagation();
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', () => { menu.style.display = 'none'; });
  menu.addEventListener('click', e => e.stopPropagation());
}
