import 'aos/dist/aos.css';
import AOS from 'aos';

export function initLandingNav() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 10);
  }, { passive: true });
}

export function initHamburger() {
  const hamburger = document.getElementById('nav-hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  const navbar = document.getElementById('navbar');
  if (!hamburger || !mobileMenu) return;

  hamburger.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', String(isOpen));
  });

  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('click', e => {
    if (!navbar?.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    }
  });
}

export function initScrollSpy() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-links a, .mobile-menu a');
  if (!navLinks.length) return;

  const basePaths = ['/index.php'];
  const firstLink = navLinks.length ? new URL(navLinks[0].href, window.location.origin).pathname : '/';
  const basePath = firstLink.replace(/\/+$/, '') || '/';
  basePaths.push(basePath);
  const isBasePath = (path) => basePaths.some(bp => path === bp || path === bp + '/');

  if (isBasePath(currentPath)) {
    const sections = document.querySelectorAll('.hero-section, .lp-hero, section[id]');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        let id = entry.target.id;
        if (entry.target.classList.contains('hero-section') || entry.target.classList.contains('lp-hero')) id = '';
        navLinks.forEach(link => {
          link.classList.remove('active');
          try {
            const url = new URL(link.href, window.location.origin);
            if (isBasePath(url.pathname)) {
              if (id === '') {
                if (!url.hash || url.hash === '#beranda') link.classList.add('active');
              } else {
                if (url.hash === '#' + id) link.classList.add('active');
              }
            }
          } catch (e) { /* ignore */ }
        });
      });
    }, { root: null, rootMargin: '-80px 0px -60% 0px', threshold: 0 });
    sections.forEach(sec => observer.observe(sec));
  } else {
    navLinks.forEach(link => {
      try {
        const linkPath = new URL(link.href, window.location.origin).pathname;
        if (!isBasePath(linkPath) && currentPath.startsWith(linkPath)) link.classList.add('active');
      } catch (e) { /* ignore */ }
    });
  }
}

export function initAOS() {
  AOS.init({ duration: 800, once: true, offset: 100 });
}

export function initLandingTheme() {
  const toggle = document.getElementById('theme-toggle');
  if (!toggle) return;

  const html = document.documentElement;

  function applyTheme(theme) {
    html.setAttribute('data-theme', theme);
    toggle.style.color = theme === 'dark' ? '#ffd000' : '#64748b';
  }

  toggle.addEventListener('click', () => {
    const current = html.getAttribute('data-theme') || 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('landing-theme', next);
  });

  const saved = localStorage.getItem('landing-theme');
  if (saved) {
    applyTheme(saved);
  } else {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(prefersDark ? 'dark' : 'light');
  }

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('landing-theme')) {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });
}
