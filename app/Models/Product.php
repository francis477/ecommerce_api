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
        'pro_price',
        'pro_details',
        'pro_stock',
        'user_id',
        'cat_id',
        'brand_id'


    ];

      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'created_at',
    ];
}
