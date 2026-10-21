export function initExportModalMasuk() {
  const modal = document.getElementById('modal-export-masuk');
  if (!modal) return;

  window.toggleExportTypeMasuk = function (type) {
    const bulanEl = document.getElementById('export_bulan_masuk');
    const tahunEl = document.getElementById('export_tahun_masuk');
    const rentangEl = document.getElementById('export_rentang_masuk');
    if (bulanEl) bulanEl.style.display = type === 'bulan' ? 'grid' : 'none';
    if (tahunEl) tahunEl.style.display = type === 'tahun' ? 'block' : 'none';
    if (rentangEl) rentangEl.style.display = type === 'rentang' ? 'grid' : 'none';
  };

  window.prepareExportFormMasuk = function () {
    const typeSelect = document.getElementById('export_tipe_masuk');
    const type = typeSelect?.value;
    let start = '';
    let end = '';

    if (type === 'bulan') {
      const m = document.getElementById('export_val_bulan_masuk')?.value;
      const y = document.getElementById('export_val_tahun_masuk')?.value;
      if (m && y) {
        start = y + '-' + m + '-01';
        const lastDay = new Date(parseInt(y), parseInt(m), 0).getDate();
        end = y + '-' + m + '-' + String(lastDay).padStart(2, '0');
      }
    } else if (type === 'tahun') {
      const y = document.getElementById('export_val_tahunonly_masuk')?.value;
      if (y) { start = y + '-01-01'; end = y + '-12-31'; }
    } else if (type === 'rentang') {
      start = document.getElementById('export_val_start_masuk')?.value || '';
      end = document.getElementById('export_val_end_masuk')?.value || '';
    }

    const hiddenStart = document.getElementById('export_start_date_masuk');
    const hiddenEnd = document.getElementById('export_end_date_masuk');
    if (hiddenStart) hiddenStart.value = start;
    if (hiddenEnd) hiddenEnd.value = end;

    setTimeout(() => {
      const closeBtn = modal.querySelector('[data-modal-close]');
      closeBtn?.click();
      modal.style.display = 'none';
    }, 500);
  };
}

export function initFilterToggleMasuk() {
  const select = document.getElementById('main_filter_type_masuk');
  if (!select) return;

  window.toggleMainFilterMasuk = function (type) {
    const bulanEl = document.getElementById('main_filter_bulan_masuk');
    const tahunEl = document.getElementById('main_filter_tahun_masuk');
    const rentangEl = document.getElementById('main_filter_rentang_masuk');
    if (bulanEl) bulanEl.style.display = type === 'bulan' ? 'block' : 'none';
    if (tahunEl) tahunEl.style.display = type === 'tahun' ? 'block' : 'none';
    if (rentangEl) rentangEl.style.display = type === 'rentang' ? 'block' : 'none';
  };

  window.toggleMainFilterMasuk(select.value);
  select.addEventListener('change', function () {
    window.toggleMainFilterMasuk(this.value);
  });
}
