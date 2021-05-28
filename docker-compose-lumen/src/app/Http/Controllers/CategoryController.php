<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * @OA\Get(
     *     path="/src/public/api/categories",
     *     operationId="src/public/api/categories",
     *     tags={"get all categories"},
     *     
     *     @OA\Response(
     *         response="200",
     *         description="Returns Categorys",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     *       security={{"bearerAuth":{}}},
     *     security={ {"bearer": {}} },
     * )
     */
    public function index()
    {
        // return response()->json(Category::all());

        $categories = Category::with('user:id,name')
            ->withCount('products')
            ->latest()
            ->paginate(20);

        //$categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    /**
     * @OA\Post(
     *     path="/src/public/api/categories",
     *     operationId="/src/public/api/categories",
     *     tags={"Create Category"},
     *       security={{"bearerAuth":{}}},
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
     *         description="Returns Categories",
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);


        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;

        auth()->user()->categories()->save($category);
        return response()->json(['message' => 'Category Added', 'category' => $category]);
    }

    /**
     * @OA\Get(
     *     path="/src/public/api/categories/{id}",
     *     operationId="/src/public/api/categories/{id}",
     *     tags={"Get Category"},
     *       security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The Category id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns Categories",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    public function show($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return response()->json(['category' => [], 'products' => []]);
        }
        $products = $category->products();
        return response()->json(['category' => $category, 'products' => $products]);
    }

    /**
     * @OA\Put(
     *     path="/src/public/api/categories/{id}",
     *     operationId="/src/public/api/categories/{id}",
     *     tags={"Update Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The Category id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Category name description",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="string", example="Category new"),
     *       @OA\Property(property="description", type="string", format="string", example="description"),
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
        $category = Category::findOrfail($id);


        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);


        $category->name = $request->name;
        $category->description = $request->description;

        return response()->json(['message' => 'Category Updated', 'category' => $category], 200);
    }
    /**
     * @OA\Delete(
     *     path="/src/public/api/categories/{id}",
     *     operationId="/src/public/api/categories/{id}",
     *     tags={"Delete Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The Category id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
    public function delete($id)
    {
        $category = Category::findOrfail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
