export function initFileNameDisplay() {
  document.querySelectorAll('[data-upload-area]').forEach(area => {
    area.addEventListener('click', () => {
      const inputId = area.dataset.uploadArea;
      const input = document.getElementById(inputId);
      if (input) input.click();
    });
  });

  const showFile = (input) => {
    const nameId = input.dataset.nameId;
    const areaId = input.dataset.areaId;
    const nameEl = nameId ? document.getElementById(nameId) : null;
    const areaEl = areaId ? document.getElementById(areaId) : null;

    if (input.files?.length) {
      const file = input.files[0];
      const sizeKB = Math.round(file.size / 1024);
      if (nameEl) {
        nameEl.textContent = `File: ${file.name} (${sizeKB} KB)`;
        nameEl.classList.remove('hidden');
      }
      if (areaEl) {
        const span = areaEl.querySelector('span');
        if (span) span.textContent = file.name;
      }
    } else {
      if (nameEl) {
        nameEl.textContent = '';
        nameEl.classList.add('hidden');
      }
    }
  };

  document.querySelectorAll('[data-file-input]').forEach(input => {
    input.addEventListener('change', () => showFile(input));
    showFile(input);
  });
}
