import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

let chartInstance = null;

function isDark() {
  return document.documentElement.classList.contains('dark');
}

function getColors() {
  return {
    grid: isDark() ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)',
    text: isDark() ? '#94a3b8' : '#64748b',
    tooltipBg: isDark() ? '#1e293b' : '#ffffff',
    tooltipTitle: isDark() ? '#f1f5f9' : '#1e293b',
    tooltipBody: isDark() ? '#cbd5e1' : '#475569',
    tooltipBorder: isDark() ? '#334155' : '#e2e8f0',
  };
}

export function initDashboardCharts() {
  const canvases = document.querySelectorAll('[data-chart]');
  canvases.forEach(canvas => {
    try {
      const raw = canvas.dataset.chart;
      if (!raw) return;
      const config = JSON.parse(raw);
      const colors = getColors();

      chartInstance = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: config.labels || [],
          datasets: (config.datasets || []).map(ds => ({
            ...ds,
            backgroundColor: ds.backgroundColor || '#0d6efd',
            borderColor: ds.borderColor || ds.backgroundColor || '#0d6efd',
            borderWidth: 1,
            borderRadius: 4,
          })),
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: { color: colors.text, padding: 16, usePointStyle: true, boxWidth: 12 },
            },
            tooltip: {
              backgroundColor: colors.tooltipBg,
              titleColor: colors.tooltipTitle,
              bodyColor: colors.tooltipBody,
              borderColor: colors.tooltipBorder,
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
            },
          },
          scales: {
            x: { grid: { color: colors.grid }, ticks: { color: colors.text } },
            y: { beginAtZero: true, grid: { color: colors.grid }, ticks: { color: colors.text } },
          },
        },
      });
    } catch (e) {
      console.warn('Chart init error:', e);
    }
  });
}

const observer = new MutationObserver(() => {
  if (!chartInstance) return;
  const colors = getColors();
  chartInstance.options.scales.x.grid.color = colors.grid;
  chartInstance.options.scales.y.grid.color = colors.grid;
  chartInstance.options.scales.x.ticks.color = colors.text;
  chartInstance.options.scales.y.ticks.color = colors.text;
  chartInstance.options.plugins.legend.labels.color = colors.text;
  chartInstance.update('none');
});
observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
