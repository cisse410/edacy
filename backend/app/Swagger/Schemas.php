<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Modèle utilisateur",
 *     required={"id", "name", "email", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", description="ID de l'utilisateur", example=1),
 *     @OA\Property(property="name", type="string", description="Nom de l'utilisateur", example="Issakha CISSE"),
 *     @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur", example="admin@edacy.sn"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Date de vérification de l'email"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création", example="2024-01-15T10:30:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour", example="2024-01-15T10:30:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Modèle produit",
 *     required={"id", "name", "price", "quantity", "created_at", "updated_at"},
 *     @OA\Property(property="id", type="integer", description="ID du produit", example=1),
 *     @OA\Property(property="name", type="string", description="Nom du produit", example="iPhone 15"),
 *     @OA\Property(property="description", type="string", nullable=true, description="Description du produit", example="Dernier smartphone Apple"),
 *     @OA\Property(property="price", type="number", format="float", description="Prix du produit", example=999.99),
 *     @OA\Property(property="quantity", type="integer", description="Quantité en stock", example=10),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création", example="2024-01-15T10:30:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de mise à jour", example="2024-01-15T10:30:00Z")
 * )
 */
class Schemas
{
    // Cette classe sert uniquement à contenir les annotations des schémas
}
