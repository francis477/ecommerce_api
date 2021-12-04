<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'm_name'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',


    ];

    public function permission(){
        return $this->hasMany(Permission::class,'model_id');
    }

}
