export function initExportModalRekap() {
  const modal = document.getElementById('modal-export-rekap');
  if (!modal) return;

  const openBtn = document.getElementById('buka-modal-export-rekap');
  if (openBtn) openBtn.addEventListener('click', () => { modal.style.display = 'flex'; });

  const close = () => { modal.style.display = 'none'; };
  modal.querySelectorAll('[data-modal-close]').forEach(btn => btn.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal.style.display === 'flex') close();
  });

  window.toggleExportTypeRekap = function (type) {
    document.getElementById('export_bulan_rekap').style.display = type === 'bulan' ? 'block' : 'none';
    document.getElementById('export_tahun_rekap').style.display = type === 'tahun' ? 'block' : 'none';
    document.getElementById('export_rentang_rekap').style.display = type === 'rentang' ? 'block' : 'none';
  };

  window.prepareExportFormRekap = function () {
    const type = document.getElementById('export_tipe_rekap').value;
    let start = '';
    let end = '';
    if (type === 'bulan') {
      const m = document.getElementById('export_val_bulan_rekap').value;
      const y = document.getElementById('export_val_tahun_rekap').value;
      start = y + '-' + m + '-01';
      const lastDay = new Date(y, m, 0).getDate();
      end = y + '-' + m + '-' + String(lastDay).padStart(2, '0');
    } else if (type === 'tahun') {
      const y = document.getElementById('export_val_tahunonly_rekap').value;
      start = y + '-01-01';
      end = y + '-12-31';
    } else if (type === 'rentang') {
      start = document.getElementById('export_val_start_rekap').value;
      end = document.getElementById('export_val_end_rekap').value;
    }
    document.getElementById('export_start_date_rekap').value = start;
    document.getElementById('export_end_date_rekap').value = end;
    setTimeout(() => { modal.style.display = 'none'; }, 500);
  };
}
