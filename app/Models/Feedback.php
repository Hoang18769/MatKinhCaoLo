<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = ['id_customer', 'id_product','id_order','id_product_variants','comment','rating','feedback_status'];
    protected $primaryKey = 'id_feedback';
    protected $table = 'feedbacks';
    public $timestamps = true;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer','id_customer');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product','id_product');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'id_order', 'id_order');
    }
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class,'id_product_variants', 'id_product_variants');
    }
}
