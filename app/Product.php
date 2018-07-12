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
    protected $fillable = ['d_name', 'd_img_url', 'is_active'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
