@extends('layouts.dashboard')

@section('title', 'Kategori')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
@endpush

@section('content')
    <div id="categories-page"
         data-data-url="{{ route('dashboard.categories.data') }}"
         data-store-url="{{ url('/dashboard/categories') }}">

        <div class="toolbar">
            <div>
                <h1>Kategori</h1>
                <p class="sub" style="margin-bottom:0;">Contoh CRUD: DataTable server-side + modal (add &amp; edit).</p>
            </div>
            <button type="button" id="btn-add">+ Tambah Kategori</button>
        </div>

        <div id="msg" class="card"></div>

        <div class="card table-card">
            <table id="cat-table" class="display">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Ikon</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="modal-backdrop" id="cat-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal">
                <div class="modal-header">
                    <h2 id="modal-title">Tambah Kategori</h2>
                    <button type="button" class="modal-close" id="modal-close" aria-label="Tutup">&times;</button>
                </div>
                <form id="category-form">
                    <div class="modal-body">
                        <input type="hidden" id="cat-id" value="">

                        <label for="cat-slug">Slug <span class="muted">(unik, contoh: minuman-dingin)</span></label>
                        <input type="text" id="cat-slug" required>

                        <label for="cat-name">Nama</label>
                        <input type="text" id="cat-name" required>

                        <label for="cat-description">Deskripsi (opsional)</label>
                        <input type="text" id="cat-description">

                        <label for="cat-icon">Icon URL (opsional)</label>
                        <input type="text" id="cat-icon" placeholder="https://...">

                        <div id="form-errors" class="error-box"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="modal-cancel">Batal</button>
                        <button type="submit" id="form-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/categories.js') }}"></script>
@endpush
