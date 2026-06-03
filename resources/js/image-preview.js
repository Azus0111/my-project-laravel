// Single image preview
export function initSinglePreview({ inputId, previewId, placeholderId, overlayId }) {
    const input       = document.getElementById(inputId);
    const preview     = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    const overlay     = document.getElementById(overlayId);

    if (!input) return;

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            overlay.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
}

// Multi image preview
export function initMultiPreview({ inputId, gridId }) {
    const input = document.getElementById(inputId);
    const grid  = document.getElementById(gridId);

    if (!input) return;

    input.addEventListener('change', function () {
        grid.innerHTML = '';

        [...this.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.className = 'relative group aspect-square rounded-xl overflow-hidden bg-base-200';
                wrap.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-black/40 opacity-0
                                group-hover:opacity-100 transition-opacity
                                flex items-end p-1.5">
                        <span class="text-white text-[10px] font-medium truncate w-full">
                            ${file.name}
                        </span>
                    </div>
                `;
                grid.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });
}