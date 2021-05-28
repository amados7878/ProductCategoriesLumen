<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class CategoryproductFactory extends Factory
{

    public function definition()
    {
        return [
            'category_id' => function() {
                return Category::all()->random();
            },

            'product_id' => function() {
                return Product::all()->random();
            },
        ];
    }

 

}

