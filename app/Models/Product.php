<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_product',
        'sku','avt_product','image_product','sortdect_product','desc_product','number_buy','price_product','sellprice_product',
        'status_product','id_category',
    ];
    protected $primaryKey = 'id_product';
    protected $appends = ['total_stock'];
    public function getTotalStockAttribute()
    {
        $variants = ProductVariant::where('id_product', $this->id_product)->get();
        return $variants->sum('quantity');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function getRelatedProducts()
    {
        if ($this->category) {
            return $this->category->products->where('id_category', '!=', $this->id_category);
        }

        return collect();
    }
    public function getImages()
    {
        return explode('#', $this->image_product);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'id_product');
    }
    public function sizes()
    {
        return $this->hasManyThrough(Size::class, ProductVariant::class, 'id_product', 'id_product_variants', 'id_product', 'id_size');
    }

    public function colors()
    {
        return $this->hasManyThrough(Color::class, ProductVariant::class, 'id_product', 'id_product_variants', 'id_product', 'id_color');
    }

    public function scopeSearch($query)
    {
        if ($key = request()->key) {
            $query = $query->where("name_category", "like", "%" . $key . "%");
        }

        return $query;
    }
    public function favoriteProducts()
    {
        return $this->hasMany(Favorite::class, 'id_favorite');
    }
    public function feedbackProducts()
    {
        return $this->hasMany(Feedback::class,'id_product');
    }
}
