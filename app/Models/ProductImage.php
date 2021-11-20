<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // protected $table ="product_images";

    protected $fillable = [
        'name',
        'pro_id'
    ];

    protected array $imageFields = [
        'image',
    ];

          /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'name',
        'pro_id'
    ];

    public $appends = [
'image_url'
    ];


    public function getImageUrlAttribute(){
        return asset('public/image/product/'.$this->name);
    }
}
