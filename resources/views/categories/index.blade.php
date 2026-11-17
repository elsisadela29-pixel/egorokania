@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('page-title', 'Manajemen Kategori Barang')
@section('page-subtitle', 'Kelola pengelompokan produk dan bahan nail art')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORM TAMBAH KATEGORI (1 Col) -->
        <div class="lg:col-span-1">
            <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft sticky top-24">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-400 to-lilac-400 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Tambah Kategori Baru</h4>
                        <p class="text-[11px] text-slate-400">Buat klasifikasi bahan nail art</p>
                    </div>
                </div>

                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nama Kategori <span class="text-brand-500">*</span>
                        </label>
                        <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}" required
                            placeholder="Contoh: Kutek Gel, Kuas Nail Art"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            placeholder="Penjelasan isi kategori ini..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all resize-none">{{ old('deskripsi') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-2xl bg-gradient-to-r from-brand-500 to-lilac-500 text-white font-bold text-xs shadow-md shadow-brand-200 hover:shadow-lg hover:scale-[1.01] transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-plus-circle"></i>
                        <span>Simpan Kategori</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- TABEL DAFTAR KATEGORI (2 Cols) -->
        <div class="lg:col-span-2">
            <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fas fa-tags text-lilac-500"></i>
                            <span>Daftar Seluruh Kategori</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">Total {{ $categories->count() }} kategori terdaftar di sistem</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-3">No</th>
                                <th class="py-3 px-3">Nama Kategori</th>
                                <th class="py-3 px-3">Deskripsi</th>
                                <th class="py-3 px-3 text-center">Jumlah Barang</th>
                                <th class="py-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-xs">
                            @forelse($categories as $index => $category)
                                <tr class="hover:bg-brand-50/30 transition-colors">
                                    <td class="py-3 px-3 text-slate-400 font-medium">{{ $index + 1 }}</td>
                                    <td class="py-3 px-3 font-bold text-slate-800">
                                        {{ $category->nama_kategori }}
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 max-w-xs">
                                        {{ $category->deskripsi ?: '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-lilac-50 border border-lilac-200 text-lilac-700">
                                            <i class="fas fa-boxes-stacked mr-1 text-[10px]"></i>
                                            {{ $category->items_count }} Item
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="inline-flex items-center space-x-1.5">
                                            <!-- Tombol Edit Modal -->
                                            <button type="button" onclick="openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->nama_kategori) }}', '{{ addslashes($category->deskripsi ?? '') }}')"
                                                class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors" title="Edit Kategori">
                                                <i class="fas fa-pen-to-square text-xs"></i>
                                            </button>

                                            <!-- Tombol Hapus (jika item 0) -->
                                            @if($category->items_count == 0)
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->nama_kategori) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-7 h-7 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors" title="Hapus Kategori">
                                                        <i class="fas fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" disabled class="w-7 h-7 rounded-xl bg-slate-100 text-slate-300 flex items-center justify-center cursor-not-allowed" title="Kategori masih dipakai oleh {{ $category->items_count }} barang">
                                                    <i class="fas fa-lock text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400">
                                        <i class="fas fa-folder-open text-3xl mb-2 text-slate-300"></i>
                                        <p>Belum ada data kategori.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- EDIT CATEGORY MODAL -->
<div id="editCategoryModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl border border-brand-100 transform transition-all">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h4 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                <i class="fas fa-pen-to-square text-blue-500"></i>
                <span>Edit Kategori Barang</span>
            </h4>
            <button onclick="closeEditCategoryModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_nama_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Kategori <span class="text-brand-500">*</span>
                </label>
                <input type="text" name="nama_kategori" id="edit_nama_kategori" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
            </div>

            <div>
                <label for="edit_deskripsi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Deskripsi Singkat
                </label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-lilac-500 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditCategoryModal(id, nama, deskripsi) {
        const form = document.getElementById('editCategoryForm');
        form.action = `/categories/${id}`;
        document.getElementById('edit_nama_kategori').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi || '';
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }

    // Close on click outside modal content
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('editCategoryModal');
        if (e.target === modal) {
            closeEditCategoryModal();
        }
    });
</script>
@endsection
