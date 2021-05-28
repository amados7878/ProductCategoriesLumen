<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use  HasFactory;
    public function products()
    {
        return $this->belongsToMany('App\Models\Product','categoriesproducts','category_id','product_id');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}