@push('scripts')
<script>
const fileStore = { img: [], word: [], pdf: [] };
const uploadAreas = { img: 'upload-area-img', word: 'upload-area-word', pdf: 'upload-area-pdf-merge' };
const lists = { img: 'img-list', word: 'word-list', pdf: 'pdf-list' };

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getIcon(type) {
    const icons = {
        img: '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        word: '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        pdf: '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
    };
    return icons[type];
}

function renderList(type) {
    const list = document.getElementById(lists[type]);
    const area = document.getElementById(uploadAreas[type]);
    list.innerHTML = '';

    if (fileStore[type].length === 0) {
        area.querySelector('span').textContent = type === 'img' ? 'Klik untuk upload foto' :
            type === 'word' ? 'Klik untuk upload Word' : 'Klik untuk upload PDF';
        return;
    }

    area.querySelector('span').textContent = fileStore[type].length + ' file dipilih';

    fileStore[type].forEach((file, index) => {
        const div = document.createElement('div');
        div.className = 'text-xs text-gray-600 dark:text-gray-400 flex items-center gap-2 py-1 px-2 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700';
        const len = fileStore[type].length;

        let previewBtn = '';
        if (file) {
            const fileUrl = URL.createObjectURL(file);
            if (type === 'word' || file.name.endsWith('.docx') || file.name.endsWith('.doc')) {
                previewBtn = `<button type="button" onclick="handleWordPreview('${fileUrl}')" class="text-green-600 hover:text-green-800 dark:text-green-400 text-xs flex items-center gap-0.5 px-1.5 py-0.5 bg-green-50 dark:bg-green-900/40 rounded border border-green-200 dark:border-green-800 font-medium" title="Pratinjau File Word">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Lihat di GDocs
                </button>`;
            } else {
                previewBtn = `<a href="${fileUrl}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 text-xs flex items-center gap-0.5 px-1.5 py-0.5 bg-blue-50 dark:bg-blue-900/40 rounded border border-blue-200 dark:border-blue-800 font-medium" title="Pratinjau File">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Lihat
                </a>`;
            }
        }

        div.innerHTML = `
            <span class="text-gray-400 dark:text-gray-500 font-mono text-xs w-5 text-right flex-shrink-0">${index + 1}</span>
            <div class="flex items-center gap-0.5 flex-shrink-0">
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 px-0.5 ${index === 0 ? 'opacity-30 cursor-not-allowed' : ''}" onclick="moveFile('${type}', ${index}, -1)" title="Pindah ke atas" ${index === 0 ? 'disabled' : ''}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                </button>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 px-0.5 ${index === len - 1 ? 'opacity-30 cursor-not-allowed' : ''}" onclick="moveFile('${type}', ${index}, 1)" title="Pindah ke bawah" ${index === len - 1 ? 'disabled' : ''}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            ${getIcon(type)}
            <span class="flex-1 truncate font-medium text-gray-700 dark:text-gray-300">${file.name}</span>
            <span class="text-gray-400">${formatBytes(file.size)}</span>
            ${previewBtn}
            <button type="button" class="text-red-500 hover:text-red-700 font-bold text-sm px-1" onclick="removeFile('${type}', ${index})" title="Hapus">×</button>
        `;
        list.appendChild(div);
    });
}

function handleFiles(type, fileList) {
    if (type === 'word') fileStore[type] = [];
    for (let i = 0; i < fileList.length; i++) {
        fileStore[type].push(fileList[i]);
    }
    renderList(type);
}

function handleWordPreview(fileUrl) {
    const host = window.location.hostname;
    const isLocal = host === 'localhost' || host === '127.0.0.1' || host.startsWith('192.168.') || host.endsWith('.test') || host.endsWith('.local');

    if (isLocal) {
        window.open(fileUrl, '_blank');
    } else {
        const gdocsUrl = 'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + fileUrl);
        window.open(gdocsUrl, '_blank');
    }
}

function removeFile(type, index) {
    fileStore[type].splice(index, 1);
    renderList(type);
}

function moveFile(type, from, direction) {
    var to = from + direction;
    var arr = fileStore[type];
    if (to < 0 || to >= arr.length) return;
    var tmp = arr[from];
    arr[from] = arr[to];
    arr[to] = tmp;
    renderList(type);
}
</script>
@endpush