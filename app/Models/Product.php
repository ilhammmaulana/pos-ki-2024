<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'price_buy', 'price_sell', 'stock', 'image', 'category_product_id'];
    public function category()
    {
        return $this->belongsTo(CategoryProduct::class);
    }
}
