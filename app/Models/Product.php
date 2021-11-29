<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

     protected $table = "products";

    protected $fillable = [
        'id',
        'pro_name',
        'old_price',
        'pro_price',
        'pro_details',
        'pro_stock',
        'category_id',
        'brand_id',
        'rating',
        'user_id'


    ];

      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'user_id'
    ];


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }


    public function product_image(){
        return $this->hasMany(ProductImage::class,'pro_id');
    }


}
