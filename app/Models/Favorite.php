<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $fillable = ['id_customer', 'id_product'];
    protected $primaryKey = 'id_favorite';
    protected $table = 'favorites';
    public $timestamps = true;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer','id_customer');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product','id_product');
    }
}
