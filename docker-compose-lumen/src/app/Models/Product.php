<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use  HasFactory;
    public function categories()

    {

        return $this->belongsToMany('App\Models\Category','categoriesproducts','product_id','category_id');
    }
    
    /**
     * Get the user that added the product.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
