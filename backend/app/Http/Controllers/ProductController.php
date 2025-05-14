<?php

namespace App\Http\Controllers;

use App\Models\Api\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Gestion des produits"
 * )
 */

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getProducts",
     *      tags={"Products"},
     *      summary="Liste des produits",
     *      description="Récupère la liste de tous les produits",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Liste des produits récupérés avec succès",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Product")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */

    public function index() : JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * @OA\Post(
     *      path="/api/products",
     *      operationId="createProduct",
     *      tags={"Products"},
     *      summary="Créer un produit",
     *      description="Crée un nouveau produit",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","price","quantity"},
     *              @OA\Property(property="name", type="string", maxLength=255, example="iPhone 15", description="Nom du produit"),
     *              @OA\Property(property="description", type="string", nullable=true, example="Dernier smartphone Apple", description="Description du produit"),
     *              @OA\Property(property="price", type="number", format="float", minimum=0, example=999.99, description="Prix du produit"),
     *              @OA\Property(property="quantity", type="integer", minimum=0, example=10, description="Quantité en stock")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Produit créé avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Produit crée avec succés !"),
     *              @OA\Property(property="product", ref="#/components/schemas/Product")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Données érronées."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      @OA\Items(type="string", example="Ce champ est obligatoire")
     *                  ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="array",
     *                      @OA\Items(type="string", example="Ce champ est obligatoire")
     *                  )
     *              )
     *          )
     *      )
     * )
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
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getProduct",
     *      tags={"Products"},
     *      summary="Afficher un produit",
     *      description="Récupère les détails d'un produit spécifique",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du produit",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Détails du produit",
     *          @OA\JsonContent(
     *              @OA\Property(property="product", ref="#/components/schemas/Product")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produit non trouvé"
     *      )
     * )
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
     * @OA\Put(
     *      path="/api/products/{id}",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      summary="Modifier un produit",
     *      description="Met à jour les informations d'un produit existant",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du produit",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", maxLength=255, example="iPhone 15 Pro", description="Nom du produit"),
     *              @OA\Property(property="description", type="string", nullable=true, example="Dernier smartphone Apple Pro", description="Description du produit"),
     *              @OA\Property(property="price", type="number", format="float", minimum=0, example=1199.99, description="Prix du produit"),
     *              @OA\Property(property="quantity", type="integer", minimum=0, example=15, description="Quantité en stock")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Produit modifié avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Produit modifié avec succés"),
     *              @OA\Property(property="product", ref="#/components/schemas/Product")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produit non trouvé"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation"
     *      )
     * )
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
     * @OA\Delete(
     *      path="/api/products/{id}",
     *      operationId="deleteProduct",
     *      tags={"Products"},
     *      summary="Supprimer un produit",
     *      description="Supprime un produit de la base de données",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID du produit",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Produit supprimé avec succès",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  oneOf={
     *                      @OA\Schema(type="string", example="Produit supprimé avec succés"),
     *                      @OA\Schema(type="null")
     *                  }
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Produit non trouvé"
     *      )
     * )
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
