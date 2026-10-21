import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;

import { registerPegawaiPicker } from './components/pegawaiPicker.js';
registerPegawaiPicker(Alpine);

Alpine.start();

import { initTheme } from './components/theme.js';
import { initSidebar, initUserDropdown } from './components/sidebar.js';
import { initDeleteModal, initFileDeleteConfirm } from './components/modal.js';
import { initClock } from './components/clock.js';
import { initFlatpickr } from './vendors/flatpickr.js';
import { initTomSelect } from './vendors/tomselect.js';

import { initExportModalMasuk, initFilterToggleMasuk } from './pages/surat-masuk.js';
import { initExportModalKeluar, initFilterToggleKeluar, initDateSync, initAutoNomorSurat } from './pages/surat-keluar.js';
import { initExportModalRekap } from './pages/kearsipan.js';
import { initDashboardCharts } from './pages/dashboard.js';
import { initPasswordToggle } from './pages/login.js';

import { initFilePreview } from './components/filePreview.js';
import { initPdfThumbnails } from './pages/tata-naskah.js';
import { initFileNameDisplay } from './pages/templates.js';
import { initLandingNav, initHamburger, initScrollSpy, initAOS, initLandingTheme } from './pages/landing.js';

document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initSidebar();
  initUserDropdown();
  initDeleteModal();
  initFileDeleteConfirm();
  initClock();
  initFlatpickr();
  initTomSelect();

  initExportModalMasuk();
  initFilterToggleMasuk();
  initExportModalKeluar();
  initFilterToggleKeluar();
  initDateSync();
  initAutoNomorSurat();
  initExportModalRekap();
  initPasswordToggle();

  initFilePreview();
  initDashboardCharts();
  initPdfThumbnails();
  initFileNameDisplay();
  initLandingNav();
  initHamburger();
  initScrollSpy();
  initAOS();
  initLandingTheme();
});
