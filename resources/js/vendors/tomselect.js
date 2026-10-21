import 'tom-select/dist/css/tom-select.css';
import TomSelect from 'tom-select';

export function initTomSelect() {
  document.querySelectorAll('.select2-searchable').forEach(el => {
    if (el.tomselect) return;
    new TomSelect(el, {
      create: false,
      sortField: { field: 'text', direction: 'asc' },
      placeholder: el.dataset.placeholder || 'Cari...',
    });
  });

  document.querySelectorAll('.tomselect-ajax').forEach(el => {
    if (el.tomselect) return;
    new TomSelect(el, {
      valueField: 'value',
      labelField: 'text',
      searchField: 'text',
      sortField: [
        { field: 'sort', direction: 'asc' },
        { field: '$score', direction: 'desc' },
      ],
      placeholder: el.dataset.placeholder || 'Cari...',
      load: (query, callback) => {
        const url = el.dataset.url;
        if (!url || !query.length) return callback();
        fetch(`${url}?q=${encodeURIComponent(query)}`)
          .then(r => r.json())
          .then(data => callback(data))
          .catch(() => callback());
      },
      render: {
        option: (item, escape) =>
          `<div class="py-1 px-2">${escape(item.text)}</div>`,
        item: (item, escape) =>
          `<div>${escape(item.text)}</div>`,
      },
    });
  });
}
