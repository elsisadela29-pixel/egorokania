<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang', 'nama_barang', 'category_id', 'merek',
        'satuan', 'harga_beli', 'jumlah_stok', 'stok_minimum'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isLowStock(): bool
    {
        return $this->jumlah_stok <= $this->stok_minimum;
    }
}
