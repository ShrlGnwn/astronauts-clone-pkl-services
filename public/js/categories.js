(function () {
    const page = document.getElementById('categories-page');
    if (!page) return;

    const dataUrl = page.dataset.dataUrl;
    const storeUrl = page.dataset.storeUrl;

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    async function api(method, url, body) {
        const res = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: body ? JSON.stringify(body) : undefined,
        });
        return { ok: res.ok, status: res.status, json: await res.json().catch(() => ({})) };
    }

    function showMsg(text, type) {
        const el = document.getElementById('msg');
        el.style.display = 'block';
        el.className = 'card ' + (type === 'ok' ? 'ok' : 'bad');
        el.textContent = text;
    }

    function showFieldErrors(errors) {
        const box = document.getElementById('form-errors');
        box.innerHTML = '';
        if (!errors) return;
        for (const field in errors) {
            errors[field].forEach((msg) => {
                const div = document.createElement('div');
                div.textContent = '• ' + msg;
                box.appendChild(div);
            });
        }
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    const modal = document.getElementById('cat-modal');

    function openModal(mode, row) {
        resetForm();
        if (mode === 'edit' && row) {
            document.getElementById('cat-id').value = row.id;
            document.getElementById('cat-slug').value = row.slug || '';
            document.getElementById('cat-name').value = row.name || '';
            document.getElementById('cat-description').value = row.description || '';
            document.getElementById('cat-icon').value = row.icon || '';
            document.getElementById('modal-title').textContent = 'Edit Kategori';
            document.getElementById('form-submit').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('modal-title').textContent = 'Tambah Kategori';
            document.getElementById('form-submit').textContent = 'Simpan';
        }
        modal.classList.add('open');
        document.getElementById('cat-name').focus();
    }

    function closeModal() {
        modal.classList.remove('open');
        resetForm();
    }

    function resetForm() {
        document.getElementById('category-form').reset();
        document.getElementById('cat-id').value = '';
        document.getElementById('form-errors').innerHTML = '';
    }

    function formData() {
        return {
            slug: document.getElementById('cat-slug').value.trim(),
            name: document.getElementById('cat-name').value.trim(),
            description: document.getElementById('cat-description').value.trim() || null,
            icon: document.getElementById('cat-icon').value.trim() || null,
        };
    }

    const table = $('#cat-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dataUrl,
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        },
        columns: [
            { data: 'name' },
            {
                data: 'slug',
                render: (d) => '<span class="muted">' + escapeHtml(d) + '</span>',
            },
            {
                data: 'description',
                render: (d) => d ? escapeHtml(d) : '<span class="muted">—</span>',
            },
            {
                data: 'icon',
                orderable: false,
                searchable: false,
                render: (d) => d
                    ? '<img class="icon" src="' + escapeHtml(d) + '" alt="">'
                    : '<span class="muted">—</span>',
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: () =>
                    '<button type="button" class="btn-sm btn-edit">Edit</button> ' +
                    '<button type="button" class="btn-sm btn-danger btn-delete">Hapus</button>',
            },
        ],
        order: [],
        ordering: false,
        pageLength: 10,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampil _MENU_',
            info: '_START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(filter dari _MAX_)',
            zeroRecords: 'Tidak ada kategori.',
            processing: 'Memuat...',
            paginate: { previous: '‹', next: '›' },
        },
    });

    document.getElementById('btn-add').addEventListener('click', () => openModal('add'));
    document.getElementById('modal-close').addEventListener('click', closeModal);
    document.getElementById('modal-cancel').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });

    document.getElementById('category-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('cat-id').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? storeUrl + '/' + id : storeUrl;

        const { ok, json } = await api(method, url, formData());
        showFieldErrors(json.errors);
        if (ok) {
            showMsg(json.message, 'ok');
            closeModal();
            table.ajax.reload(null, false);
        } else if (json.errors) {
            showMsg('Cek kembali isian form.', 'bad');
        } else {
            showMsg(json.message || 'Terjadi kesalahan.', 'bad');
        }
    });

    $('#cat-table tbody').on('click', '.btn-edit', function () {
        const row = table.row($(this).closest('tr')).data();
        openModal('edit', row);
    });

    $('#cat-table tbody').on('click', '.btn-delete', async function () {
        const row = table.row($(this).closest('tr')).data();
        if (!confirm('Hapus kategori "' + row.name + '"? Aksi tidak bisa dibatalkan.')) return;

        const { ok, json } = await api('DELETE', storeUrl + '/' + row.id);
        if (ok) {
            showMsg(json.message, 'ok');
            table.ajax.reload(null, false);
        } else {
            showMsg(json.message || 'Gagal menghapus.', 'bad');
        }
    });
})();
