<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $fillable = [
        'desc_color',
    ];
    protected $primaryKey = 'id_color';
    public function colors()
    {
        return $this->hasMany(ProductVariant::class, 'id_color');
    }
}
