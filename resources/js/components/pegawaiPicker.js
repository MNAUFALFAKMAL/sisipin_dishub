export function registerPegawaiPicker(Alpine) {
  Alpine.data('tujuanPegawai', (daftar, initial) => ({
    open: false,
    q: '',
    daftar: Array.isArray(daftar) ? daftar : [],
    selected: [],
    tujuanText: initial || '',

    init() {
      if (initial) {
        this.selected = String(initial)
          .split(';')
          .map((s) => s.replace(/^\s*\d+\.\s*/, '').trim())
          .filter((n) => this.daftar.some((d) => d.nama === n));
      }
    },

    get grouped() {
      const needle = this.q.toLowerCase().trim();
      const groups = [];
      const index = new Map();

      this.daftar.forEach((d) => {
        if (needle && !d.nama.toLowerCase().includes(needle)) return;
        const bidang = d.bidang || 'Tanpa Bidang';
        if (!index.has(bidang)) {
          index.set(bidang, groups.length);
          groups.push({ bidang, items: [] });
        }
        groups[index.get(bidang)].items.push(d.nama);
      });

      return groups;
    },

    get count() {
      return this.selected.length;
    },

    isSelected(name) {
      return this.selected.includes(name);
    },

    toggle(name) {
      const i = this.selected.indexOf(name);
      if (i >= 0) {
        this.selected.splice(i, 1);
      } else {
        this.selected.push(name);
      }
    },

    buildText() {
      return this.selected.map((n, i) => `${i + 1}. ${n}`).join('; ');
    },

    apply() {
      if (this.selected.length) {
        this.tujuanText = this.buildText();
      }
      this.open = false;
    },

    clearAll() {
      this.selected = [];
      this.tujuanText = '';
    },
  }));
}