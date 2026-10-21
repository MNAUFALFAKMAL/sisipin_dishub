export function initDeleteModal() {
  // Support legacy component pattern: #confirm-modal with [data-cancel] etc.
  const legacyModal = document.getElementById('confirm-modal');
  if (legacyModal) {
    let activeForm = null;
    const cancelBtn = legacyModal.querySelector('[data-cancel]');
    const confirmBtn = legacyModal.querySelector('[data-confirm]');
    const overlay = legacyModal.querySelector('[data-overlay]');

    const show = () => legacyModal.classList.remove('hidden');
    const hide = () => legacyModal.classList.add('hidden');

    document.querySelectorAll('.delete-trigger').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        activeForm = btn.closest('form');
        show();
      });
    });

    cancelBtn?.addEventListener('click', hide);
    overlay?.addEventListener('click', hide);
    confirmBtn?.addEventListener('click', () => {
      if (activeForm) activeForm.submit();
      hide();
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') hide();
    });
  }

  // Support blade inline pattern: #hapusModal with #batalHapusModal / #konfirmasiHapusModal
  const bladeModal = document.getElementById('hapusModal');
  if (bladeModal) {
    let activeForm = null;
    const batalBtn = document.getElementById('batalHapusModal');
    const konfirmBtn = document.getElementById('konfirmasiHapusModal');

    const show = () => { bladeModal.style.display = 'flex'; bladeModal.classList.remove('hidden'); };
    const hide = () => { bladeModal.style.display = 'none'; bladeModal.classList.add('hidden'); };

    document.querySelectorAll('.delete-trigger').forEach(btn => {
      btn.addEventListener('click', function () {
        activeForm = this.closest('form');
        show();
      });
    });

    batalBtn?.addEventListener('click', hide);
    konfirmBtn?.addEventListener('click', () => {
      if (activeForm) activeForm.submit();
      hide();
    });

    bladeModal.addEventListener('click', function (e) {
      if (e.target === bladeModal) hide();
    });
  }
}

export function initFileDeleteConfirm() {
  // Support legacy component pattern: [data-file-delete-checkbox] + #file-delete-modal
  document.querySelectorAll('[data-file-delete-checkbox]').forEach(checkbox => {
    let confirmed = false;
    const modal = document.getElementById('file-delete-modal');
    if (!modal) return;

    const cancelBtn = modal.querySelector('[data-cancel]');
    const confirmBtn = modal.querySelector('[data-confirm]');
    const overlay = modal.querySelector('[data-overlay]');

    const show = () => modal.classList.remove('hidden');
    const hide = () => { modal.classList.add('hidden'); };

    checkbox.addEventListener('change', function () {
      if (this.checked && !confirmed) {
        show();
      }
    });

    cancelBtn?.addEventListener('click', () => { checkbox.checked = false; hide(); });
    overlay?.addEventListener('click', () => { checkbox.checked = false; hide(); });
    confirmBtn?.addEventListener('click', () => { confirmed = true; hide(); });
  });

  // Support blade pattern: input[name="hapus_file"] + #confirmHapusModal
  document.querySelectorAll('input[name="hapus_file"]').forEach(checkbox => {
    let hapusConfirmed = false;
    const modal = document.getElementById('confirmHapusModal');
    if (!modal) return;

    const batalBtn = document.getElementById('batalHapusBtn');
    const konfirmBtn = document.getElementById('konfirmasiHapusBtn');

    const show = () => { modal.style.display = 'flex'; modal.classList.remove('hidden'); };
    const hide = () => { modal.style.display = 'none'; modal.classList.add('hidden'); };

    checkbox.addEventListener('change', function () {
      if (this.checked && !hapusConfirmed) {
        show();
        this.checked = false;
      }
    });

    batalBtn?.addEventListener('click', () => { checkbox.checked = false; hapusConfirmed = false; hide(); });
    konfirmBtn?.addEventListener('click', () => { checkbox.checked = true; hapusConfirmed = true; hide(); });

    modal.addEventListener('click', function (e) {
      if (e.target === modal) { checkbox.checked = false; hapusConfirmed = false; hide(); }
    });
  });
}
