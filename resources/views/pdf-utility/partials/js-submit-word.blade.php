@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('word-input').addEventListener('change', function() { handleFiles('word', this.files); });
    document.getElementById('form-word').addEventListener('submit', function(e) {
        e.preventDefault();
        if (fileStore['word'].length === 0) { alert('Pilih minimal 1 file'); return; }
        var btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        var fd = new FormData();
        fd.append('_token', this.querySelector('input[name="_token"]').value);
        var titleInp = this.querySelector('input[name="title"]');
        if (titleInp && titleInp.value) fd.append('title', titleInp.value);
        fileStore['word'].forEach(function(f) { fd.append('word_file', f); });
        fetch('{{ route("pdf-utility.word-to-pdf") }}', { method: 'POST', body: fd })
            .then(function(r) {
                if (!r.ok) throw new Error('Gagal diproses');
                var contentType = r.headers.get('Content-Type') || '';
                if (contentType.indexOf('application/pdf') === -1) throw new Error('Bukan PDF');
                var disposition = r.headers.get('Content-Disposition') || '';
                var match = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                var filename = match ? match[1].replace(/['"]/g, '') : 'download.pdf';
                return r.blob().then(function(blob) { return { blob: blob, filename: filename }; });
            })
            .then(function(result) {
                var url = URL.createObjectURL(result.blob);
                var a = document.createElement('a');
                a.href = url; a.download = result.filename;
                document.body.appendChild(a); a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            })
            .catch(function() { alert('Server mengembalikan error. Silakan coba lagi.'); })
            .finally(function() { btn.disabled = false; btn.textContent = 'Konversi ke PDF'; });
    });
});
</script>
@endpush