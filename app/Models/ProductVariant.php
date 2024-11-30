<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_product','id_color', 'id_size','quantity',
    ];
    protected $primaryKey = 'id_product_variants';
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'id_size');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'id_color');
    }
    // Định nghĩa quan hệ với OrderDetail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class,'id_orderdetail ' );
    }
    public function feedbackProductVariant()
    {
        return $this->hasMany(Feedback::class,'id_product_variants');
    }
}
