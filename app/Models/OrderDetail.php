<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = ['quantity','totalprice','id_order','id_product','id_product_variants'];
    protected $primaryKey = 'id_orderdetail';
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'id_product');
    }
    public function sizes()
    {
        return $this->hasManyThrough(Size::class, ProductVariant::class, 'id_product_variants', 'id_size', 'id_product_variants', 'id_size');
    }

    public function colors()
    {
        return $this->hasManyThrough(Color::class, ProductVariant::class, 'id_product_variants', 'id_color', 'id_product_variants', 'id_color');
    }
    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id_product_variants', 'id_product', 'id_product_variants', 'id_product');
    }
    public function product1()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'id_product_variants', 'id_product_variants');
    }
}
