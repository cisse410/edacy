<?php

namespace App\Http\Controllers;

use App\Models\Api\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function index() : JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ], [
            'name.required' => 'Ce champ est obligatoire',
            'price.required' => 'Ce champ est obligatoire',
            'quantity.required' => 'Ce champ est obligatoire'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Produit crée avec succés !',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     * @param Product $product
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function show(Product $product) : JsonResponse
    {
        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Illuminate\Http\Request $request
     * @param Product $product
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($request->all());

        return response()->json([
            'message' => 'Produit modifié avec succés',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function destroy(Product $product) : JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succés',
            null
        ]);
    }
}
