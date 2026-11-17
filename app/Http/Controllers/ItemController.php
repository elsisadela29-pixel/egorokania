<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        // Pencarian berdasarkan nama, kode, atau merek
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan status stok
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->whereColumn('jumlah_stok', '<=', 'stok_minimum')->where('jumlah_stok', '>', 0);
            } elseif ($request->status === 'empty') {
                $query->where('jumlah_stok', '<=', 0);
            } elseif ($request->status === 'safe') {
                $query->whereColumn('jumlah_stok', '>', 'stok_minimum');
            }
        }

        $items = $query->orderBy('nama_barang', 'asc')->paginate(15)->withQueryString();
        $categories = Category::orderBy('nama_kategori', 'asc')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama_kategori', 'asc')->get();
        // Generate automatic recommended item code if possible
        $lastItem = Item::latest('id')->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $suggestedCode = 'NA-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('items.create', compact('categories', 'suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:items,kode_barang',
            'nama_barang' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'merek' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'jumlah_stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode / ID barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang ini sudah ada, gunakan kode lain.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'satuan.required' => 'Satuan barang wajib diisi (contoh: Botol, Pcs, Box).',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'jumlah_stok.required' => 'Jumlah stok awal wajib diisi.',
            'stok_minimum.required' => 'Batas stok minimum wajib diisi.',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Barang nail art baru berhasil ditambahkan!');
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('nama_kategori', 'asc')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:items,kode_barang,' . $item->id,
            'nama_barang' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'merek' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'jumlah_stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode / ID barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang ini sudah digunakan oleh barang lain.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'satuan.required' => 'Satuan barang wajib diisi.',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Data barang nail art berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        $nama = $item->nama_barang;
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', "Barang '{$nama}' berhasil dihapus dari inventaris.");
    }
}
