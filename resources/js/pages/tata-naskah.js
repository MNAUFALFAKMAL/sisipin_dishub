import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

export async function initPdfThumbnails() {
  const canvases = document.querySelectorAll('.tn-pdf-thumb');
  if (!canvases.length) return;

  const pdfjsLib = await import('pdfjs-dist');
  pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const canvas = entry.target;
      const url = canvas.dataset.pdfUrl;
      obs.unobserve(canvas);

      const loadingTask = pdfjsLib.getDocument(url);
      loadingTask.promise
        .then(pdf => pdf.getPage(1))
        .then(page => {
          const context = canvas.getContext('2d');
          const viewport = page.getViewport({ scale: 2.0 });
          canvas.height = viewport.height;
          canvas.width = viewport.width;
          return page.render({ canvasContext: context, viewport }).promise;
        })
        .then(() => {
          canvas.classList.add('loaded');
          const loader = canvas.previousElementSibling;
          if (loader?.classList.contains('tn-thumb-loader')) loader.style.display = 'none';
        })
        .catch(err => {
          console.error('Error rendering PDF thumbnail:', err);
          const loader = canvas.previousElementSibling;
          if (loader) loader.style.display = 'none';
        });
    });
  }, { rootMargin: '150px' });

  canvases.forEach(canvas => observer.observe(canvas));
}
