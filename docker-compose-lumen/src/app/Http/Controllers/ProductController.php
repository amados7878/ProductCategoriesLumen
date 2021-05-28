<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * @OA\Post(
     *     path="/src/public/api/products/{categoryId}",
     *     operationId="/src/public/api/products/{categoryId}",
     *     tags={"Create Product"},
     *       security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         description="The Category id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Product name description",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="string", example="Product new"),
     *       @OA\Property(property="description", type="string", format="string", example="description"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Products",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *    
     *     security={ {"bearer": {}} },
     * )
     */
    public function store(Request $request, $categoryId)
    {
        $category = Category::findOrfail($categoryId);

        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->user_id = auth()->user()->id;

        $category->products()->save($product);
        $product->categories()->attach($category->category_id);
        return response()->json(['message' => 'Product Added', 'product' => $product]);
    }

     /**
     * @OA\Put(
     *     path="/src/public/api/products/{id}",
     *     operationId="/src/public/api/products/{id}",
     *     tags={"Update Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The Product id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Category name description",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="string", example="Category new"),
     *       @OA\Property(property="description", type="string", format="string", example="discription"),
     *    ),
     * ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Categorys",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function update($id, Request $request)
    {
        $product = Product::findOrfail($id);
        if (auth()->user()->id !== $product->user_id) {
            return response()->json(['message' => 'Action Forbidden']);
        }
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();

        return response()->json(['message' => 'Product Updated', 'product' => $product]);
    }

/**
     * @OA\Delete(
     *     path="/src/public/api/products/{id}",
     *     operationId="/src/public/api/products/{id}",
     *     tags={"Delete Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The Product id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Products",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
    public function delete($id)
    {
        $product = Product::findOrfail($id);
        if (auth()->user()->id !== $product->user_id) {
            return response()->json(['message' => 'Action Forbidden']);
        }
       
        $product->delete();
        return response()->json(null, 204);
    }
}
