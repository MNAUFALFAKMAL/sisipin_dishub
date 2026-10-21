let previewModal = null;

export function initFilePreview() {
  const inputs = document.querySelectorAll('input[type="file"][name="file_surat[]"]');
  inputs.forEach(input => {
    const container = input.parentElement.querySelector('.file-preview-grid');
    if (!container) return;

    let currentFiles = [];

    const handleRemove = (index) => {
      currentFiles = currentFiles.filter((_, i) => i !== index);
      const dt = new DataTransfer();
      currentFiles.forEach(f => dt.items.add(f));
      input.files = dt.files;
      renderPreviewGrid(container, currentFiles, handleRemove);
    };

    input.addEventListener('change', () => {
      currentFiles = Array.from(input.files);
      renderPreviewGrid(container, currentFiles, handleRemove);
    });
  });
}

function renderPreviewGrid(container, files, onRemove) {
  container.innerHTML = '';
  if (files.length === 0) return;

  const grid = document.createElement('div');
  grid.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mt-3';

  files.forEach((file, i) => {
    const isImage = file.type.startsWith('image/');
    const card = document.createElement('div');
    card.className = 'file-preview-card-new';

    const thumb = document.createElement('div');
    thumb.className = 'file-preview-thumb';

    if (isImage) {
      const img = document.createElement('img');
      img.alt = file.name;
      const reader = new FileReader();
      reader.onload = e => { img.src = e.target.result; };
      reader.readAsDataURL(file);
      thumb.appendChild(img);
    } else {
      thumb.innerHTML = `<svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6"/></svg>`;
    }

    card.appendChild(thumb);

    const info = document.createElement('div');
    info.className = 'file-preview-info';

    const name = document.createElement('span');
    name.className = 'file-preview-name';
    name.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;

    const size = document.createElement('span');
    size.className = 'file-preview-size';
    size.textContent = formatSize(file.size);

    info.appendChild(name);
    info.appendChild(size);
    card.appendChild(info);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'file-preview-remove';
    removeBtn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
    removeBtn.addEventListener('click', e => {
      e.stopPropagation();
      if (onRemove) onRemove(i);
    });
    card.appendChild(removeBtn);

    card.addEventListener('click', () => {
      if (isImage) {
        const reader = new FileReader();
        reader.onload = e => openPreviewModal({ url: e.target.result, type: 'image', name: file.name });
        reader.readAsDataURL(file);
      } else {
        const url = URL.createObjectURL(file);
        openPreviewModal({ url, type: 'pdf', name: file.name });
      }
    });

    grid.appendChild(card);
  });

  container.appendChild(grid);
}

function formatSize(bytes) {
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1048576).toFixed(1) + ' MB';
}

export function openPreviewModal(file) {
  closePreviewModal();

  const overlay = document.createElement('div');
  overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4';
  overlay.style.animation = 'fadeIn 0.15s ease-out';

  const modal = document.createElement('div');
  modal.className = 'relative flex w-full max-w-4xl flex-col rounded-xl bg-white shadow-2xl dark:bg-gray-900 max-h-[90vh]';
  modal.style.animation = 'scaleIn 0.15s ease-out';

  const header = document.createElement('div');
  header.className = 'flex items-center justify-between border-b px-6 py-3 dark:border-gray-700';

  const title = document.createElement('h3');
  title.className = 'text-sm font-semibold text-gray-800 dark:text-gray-200 truncate';
  title.textContent = file.name || 'Pratinjau';

  const actions = document.createElement('div');
  actions.className = 'flex items-center gap-2';

  if (file.type === 'pdf') {
    const openBtn = document.createElement('a');
    openBtn.href = file.url;
    openBtn.target = '_blank';
    openBtn.className = 'rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-400';
    openBtn.textContent = 'Buka di Tab Baru';
    actions.appendChild(openBtn);
  }

  const closeBtn = document.createElement('button');
  closeBtn.type = 'button';
  closeBtn.className = 'rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300';
  closeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
  closeBtn.addEventListener('click', closePreviewModal);
  actions.appendChild(closeBtn);

  header.appendChild(title);
  header.appendChild(actions);
  modal.appendChild(header);

  const body = document.createElement('div');
  body.className = 'flex-1 overflow-auto p-2';

  if (file.type === 'image') {
    const img = document.createElement('img');
    img.src = file.url;
    img.className = 'w-full';
    img.style.maxHeight = '75vh';
    img.style.objectFit = 'contain';
    body.appendChild(img);
  } else {
    const embed = document.createElement('embed');
    embed.src = file.url + '#toolbar=1';
    embed.type = 'application/pdf';
    embed.className = 'w-full border-0';
    embed.style.height = '75vh';
    body.appendChild(embed);
  }

  modal.appendChild(body);
  overlay.appendChild(modal);

  overlay.addEventListener('click', e => {
    if (e.target === overlay) closePreviewModal();
  });

  document.addEventListener('keydown', closeOnEscape);
  document.body.style.overflow = 'hidden';
  document.body.appendChild(overlay);
  previewModal = overlay;
}

function closeOnEscape(e) {
  if (e.key === 'Escape') closePreviewModal();
}

function closePreviewModal() {
  if (previewModal) {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
    previewModal.remove();
    previewModal = null;
  }
}

window.openFilePreview = openPreviewModal;
