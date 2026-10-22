// Surat Search Component - Alpine.js
// Usage: <div x-data="suratSearch()">

document.addEventListener('alpine:init', () => {
    Alpine.data('suratSearch', (config = {}) => ({
        // Configuration
        searchEndpoint: config.searchEndpoint || '/surat-masuk/search',
        searchParam: config.searchParam || 'search',
        
        // State
        search: '',
        filters: {
            tahun: '',
            status: '',
            bidang: '',
            klasifikasi: '',
            jenis: '',
            date_from: '',
            date_to: '',
        },
        loading: false,
        results: [],
        total: 0,
        debounceTimer: null,
        
        // Methods
        async fetch() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(async () => {
                this.loading = true;
                
                const params = new URLSearchParams();
                if (this.search) params.set('search', this.search);
                Object.entries(this.filters).forEach(([key, value]) => {
                    if (value) params.set(key, value);
                });
                
                // Preserve jenis_surat and tahun from URL
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('jenis_surat')) params.set('jenis_surat', urlParams.get('jenis_surat'));
                if (urlParams.has('tahun')) params.set('tahun', urlParams.get('tahun'));
                
                try {
                    const response = await fetch(`${this.searchEndpoint}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    
                    if (!response.ok) throw new Error('Search failed');
                    
                    const data = await response.json();
                    this.results = data.data || [];
                    this.total = data.total || 0;
                    
                    // Update URL without reload
                    const newUrl = `${window.location.pathname}?${new URLSearchParams({
                        search: this.search || undefined,
                        ...this.getActiveFilters(),
                        tahun: urlParams.get('tahun') || undefined,
                        jenis_surat: urlParams.get('jenis_surat') || undefined,
                    }).toString()}`;
                    
                    window.history.replaceState({}, '', this.search || this.hasActiveFilters() ? newUrl : window.location.pathname);
                    
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    this.loading = false;
                }
            }, 300);
        },
        
        getActiveFilters() {
            const filters = {};
            Object.entries(this.filters).forEach(([key, value]) => {
                if (value) filters[key] = value;
            });
            return filters;
        },
        
        hasActiveFilters() {
            return Object.values(this.filters).some(v => v);
        },
        
        resetFilters() {
            this.filters = {
                tahun: '',
                status: '',
                bidang: '',
                klasifikasi: '',
                jenis: '',
                date_from: '',
                date_to: '',
            };
            this.search = '';
            this.fetch();
        },
        
        // For select inputs
        updateFilter(key, value) {
            this.filters[key] = value;
            this.fetch();
        },
    }));
});