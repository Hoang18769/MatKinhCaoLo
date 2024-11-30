<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        'desc_size',
    ];
    protected $primaryKey = 'id_size';
    public function sizes()
    {
        return $this->hasMany(ProductVariant::class, 'id_size');
    }
}
