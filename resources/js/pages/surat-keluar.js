export function initExportModalKeluar() {
  const modal = document.getElementById('modal-export-keluar');
  if (!modal) return;

  window.toggleExportTypeKeluar = function (type) {
    const bulanEl = document.getElementById('export_bulan_keluar');
    const tahunEl = document.getElementById('export_tahun_keluar');
    const rentangEl = document.getElementById('export_rentang_keluar');
    if (bulanEl) bulanEl.style.display = type === 'bulan' ? 'grid' : 'none';
    if (tahunEl) tahunEl.style.display = type === 'tahun' ? 'block' : 'none';
    if (rentangEl) rentangEl.style.display = type === 'rentang' ? 'grid' : 'none';
  };

  window.prepareExportFormKeluar = function () {
    const typeSelect = document.getElementById('export_tipe_keluar');
    const type = typeSelect?.value;
    let start = '';
    let end = '';

    if (type === 'bulan') {
      const m = document.getElementById('export_val_bulan_keluar')?.value;
      const y = document.getElementById('export_val_tahun_keluar')?.value;
      if (m && y) {
        start = y + '-' + m + '-01';
        const lastDay = new Date(parseInt(y), parseInt(m), 0).getDate();
        end = y + '-' + m + '-' + String(lastDay).padStart(2, '0');
      }
    } else if (type === 'tahun') {
      const y = document.getElementById('export_val_tahunonly_keluar')?.value;
      if (y) { start = y + '-01-01'; end = y + '-12-31'; }
    } else if (type === 'rentang') {
      start = document.getElementById('export_val_start_keluar')?.value || '';
      end = document.getElementById('export_val_end_keluar')?.value || '';
    }

    const hiddenStart = document.getElementById('export_start_date_keluar');
    const hiddenEnd = document.getElementById('export_end_date_keluar');
    if (hiddenStart) hiddenStart.value = start;
    if (hiddenEnd) hiddenEnd.value = end;

    setTimeout(() => {
      const closeBtn = modal.querySelector('[data-modal-close]');
      closeBtn?.click();
      modal.style.display = 'none';
    }, 500);
  };
}

export function initFilterToggleKeluar() {
  const select = document.getElementById('main_filter_type_keluar');
  if (!select) return;

  window.toggleMainFilterKeluar = function (type) {
    const bulanEl = document.getElementById('main_filter_bulan_keluar');
    const tahunEl = document.getElementById('main_filter_tahun_keluar');
    const rentangEl = document.getElementById('main_filter_rentang_keluar');
    if (bulanEl) bulanEl.style.display = type === 'bulan' ? 'block' : 'none';
    if (tahunEl) tahunEl.style.display = type === 'tahun' ? 'block' : 'none';
    if (rentangEl) rentangEl.style.display = type === 'rentang' ? 'block' : 'none';
  };

  window.toggleMainFilterKeluar(select.value);
  select.addEventListener('change', function () {
    window.toggleMainFilterKeluar(this.value);
  });
}

export function initDateSync() {
  // Hanya aktif di form Surat Keluar (create/edit) — bukan form Surat Masuk
  if (!document.getElementById('tujuan')) return;

  const tanggalTerima = document.getElementById('tanggal_terima');
  const tanggalSurat = document.getElementById('tanggal_surat');
  if (!tanggalTerima || !tanggalSurat) return;

  function triggerSuratUpdate() {
    setTimeout(() => {
      try {
        const evt = new Event('input', { bubbles: true });
        document.getElementById('no_agenda')?.dispatchEvent(evt);
      } catch (e) {}
    }, 50);
  }

  const syncToSurat = function () {
    if (tanggalTerima.value) tanggalSurat.value = tanggalTerima.value;
    triggerSuratUpdate();
  };
  const syncToTerima = function () {
    if (tanggalSurat.value) tanggalTerima.value = tanggalSurat.value;
    triggerSuratUpdate();
  };

  tanggalTerima.addEventListener('input', syncToSurat);
  tanggalTerima.addEventListener('change', syncToSurat);
  tanggalSurat.addEventListener('input', syncToTerima);
  tanggalSurat.addEventListener('change', syncToTerima);
}

export function initAutoNomorSurat() {
  // Hanya aktif di form Surat Keluar (create/edit) — bukan form Surat Masuk
  if (!document.getElementById('tujuan')) return;

  const noAgenda = document.getElementById('no_agenda');
  const klasifikasiSelect = document.getElementById('klasifikasi_id');
  const tanggalSurat = document.getElementById('tanggal_surat');
  const noSurat = document.getElementById('no_surat');
  if (!noAgenda || !noSurat) return;

  function getKlasifikasiKode() {
    if (!klasifikasiSelect) return '';
    let selectedText = '';
    if (klasifikasiSelect.tomselect) {
      const val = klasifikasiSelect.tomselect.getValue();
      if (val) {
        const item = klasifikasiSelect.tomselect.options[val];
        if (item) selectedText = item.text;
      }
    } else {
      const opt = klasifikasiSelect.options[klasifikasiSelect.selectedIndex];
      if (opt) selectedText = opt.text;
    }
    if (selectedText && selectedText.includes(' - ')) {
      return selectedText.split(' - ')[0].trim();
    }
    return '';
  }

  function update() {
    const kode = getKlasifikasiKode();
    const agenda = noAgenda.value.trim();
    let tahun = '';
    if (tanggalSurat?.value) tahun = tanggalSurat.value.split('-')[0];
    if (tanggalSurat?.value && !tahun) {
      const y = new Date(tanggalSurat.value).getFullYear();
      if (y) tahun = String(y);
    }
    if (!kode || !agenda || !tahun) {
      if (noSurat.value) return;
      noSurat.value = '';
      return;
    }
    noSurat.value = `${kode}/${agenda}/V.13/${tahun}`;
  }

  noAgenda.addEventListener('input', update);
  noAgenda.addEventListener('change', update);

  if (klasifikasiSelect) {
    if (klasifikasiSelect.tomselect) {
      klasifikasiSelect.tomselect.on('change', () => setTimeout(update, 100));
    } else {
      klasifikasiSelect.addEventListener('change', () => setTimeout(update, 100));
    }
  }

  tanggalSurat?.addEventListener('change', update);
  tanggalSurat?.addEventListener('input', update);

  setTimeout(update, 300);
}
