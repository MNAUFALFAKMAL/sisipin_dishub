import 'flatpickr/dist/flatpickr.min.css';
import 'flatpickr/dist/themes/light.css';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

export function initFlatpickr() {
  const inputs = document.querySelectorAll('input[type="date"]');
  if (!inputs.length) return;

  flatpickr(inputs, {
    locale: Indonesian,
    altInput: true,
    altFormat: 'd F Y',
    dateFormat: 'Y-m-d',
    allowInput: true,
    parseDate: (dateStr) => {
      if (!dateStr) return null;
      const trimmed = dateStr.trim();
      const isoMatch = trimmed.match(/^(\d{4})-(\d{2})-(\d{2})$/);
      if (isoMatch) return new Date(+isoMatch[1], +isoMatch[2] - 1, +isoMatch[3]);
      const idMatch = trimmed.match(/^(\d{1,2})\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+(\d{4})$/i);
      if (idMatch) {
        const months = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'];
        return new Date(+idMatch[3], months.indexOf(idMatch[2].toLowerCase()), +idMatch[1]);
      }
      const numMatch = trimmed.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
      if (numMatch) return new Date(+numMatch[3], +numMatch[2] - 1, +numMatch[1]);
      return null;
    },
  });
}
