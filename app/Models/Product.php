<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "name",
        "brand_id",
        "category_id",
        "slug",
        "price",
        "discount_percent",
        "views",
        "description",
        "image",
        "status",
    ];

    public function category() {
        return $this->belongsTo(Category::class, "category_id", "id");
    }

    public function brand() {
        return $this->belongsTo(Brand::class, "brand_id", "id");
    }

    public function getFinalPriceAttribute() {
        return round($this->price * (1 - $this->discount_percent / 100));
    }
}
