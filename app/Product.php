<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are assignable.
     *
     * @var array
     */
    protected $fillable = ['product_name', 'product_img_url', 'product_price', 'is_active'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
