<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'discount', 'limit_number', 'number_used', 'expiration_date','payment_limit'];
    protected $primaryKey = 'id_discount';
    public $timestamps = true;
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_order');
    }
}
