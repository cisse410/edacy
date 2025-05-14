<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API CRUD - Phase 2 | Edacy",
 *      description="Documentation de L'API d'un CRUD pour validation de la phase 2 du programme propose par Edacy",
 *
 *       @OA\Contact(
 *          email= "admin@edacy.sn",
 *          name="Issakha CISSE"
 *        )
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8000",
 *      description="Adresse de l'API"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      description="Utilisez le token retourné lors du login. Format: Bearer {token}"
 * )
 */

class SwaggerController extends Controller
{
    //
}
