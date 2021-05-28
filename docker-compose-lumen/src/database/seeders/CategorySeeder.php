<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productcategory;
use App\Database\Factories;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($i=0;$i<=5;$i++)
        {
            
             $category = Category::factory(App\Model\Category::class)->create();
             for($i=0;$i<=15;$i++)
             {
                  $product = Product::factory(App\Model\Product::class)->create();
                  $product->categories()->attach($category->id);
             }

        }
       
        
    }
}
