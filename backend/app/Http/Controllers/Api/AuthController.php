<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints d'authentification"
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="Créer un nouveau compte utilisateur",
     *      description="Enregistre un nouvel utilisateur et retourne un token d'authentification",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Issakha CISSE", description="Nom de l'utilisateur"),
     *              @OA\Property(property="email", type="string", format="email", example="admin@edacy.sn", description="Adresse email"),
     *              @OA\Property(property="password", type="string", format="password", minLength=8, example="password123", description="Mot de passe (min 8 caractères)"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password123", description="Confirmation du mot de passe")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Utilisateur créé avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0"),
     *              @OA\Property(property="token_type", type="string", example="Bearer"),
     *              @OA\Property(property="message", type="string", example="Bienvenue ! Vous pouvez vous connectez maintenant."),
     *              @OA\Property(property="User", ref="#/components/schemas/User")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Erreur lors de la création de votre compte."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(type="string", example="Ce mail existe déjà")
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(type="string", example="Mot de passe trop court (min 8 caractères)")
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @author CISSE410 <a href="https://www.github.com/cisse410>#CISSE410</a>
     */

    public function register(Request $request): JsonResponse{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ],[
            'name.required' => 'Ce champ est obligatoire',
            'name.min' => 'Le nom est trop court (min 3 caractères)',
            'name.max' => 'Le nom est trop long (max 255 caractères)',
            'email.required' => 'Ce champ est obligatoire',
            'email.email' => 'Saisir un mail valide',
            'email.unique' => 'Ce mail existe déjà',
            'password.required' => 'Ce champ est obligatoire',
            'password.min' => 'Mot de passe trop court (min 8 caractères)'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Bienvenue ! Vous pouvez vous connectez maintenant.',
            'user' => $user,
        ], 201);
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="Connexion utilisateur",
     *      description="Authentifie un utilisateur et retourne un token d'accès",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="admin@edacy.sn", description="Adresse email"),
     *              @OA\Property(property="password", type="string", format="password", example="password123", description="Mot de passe")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Connexion réussie",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string", example="1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0"),
     *              @OA\Property(property="token_type", type="string", example="Bearer"),
     *              @OA\Property(property="message", type="string", example="Content de vous revoir !"),
     *              @OA\Property(property="user", ref="#/components/schemas/User")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Identifiants invalides",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Erreur lors de la connexion."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(type="string", example="Les informations de connexion fournies sont incorrectes.")
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @throws \Illuminate\Validation\ValidationException
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */

    public function login(Request $request) : JsonResponse {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],[
            'email.required' => 'Ce champ est obligatoire',
            'email.email' => 'Saisir un mail valide',
            'password.required' => 'Ce champ est obligatoire',
        ]);

        // Verification de la correspondance entre les informations fournies par l'utilisateur
        // et celles stockées en base
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Les informations de connexion fournies sont incorrectes.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Content de vous revoir !',
            'user' => $user,
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logout",
     *      tags={"Authentication"},
     *      summary="Déconnexion utilisateur",
     *      description="Révoque le token d'authentification actuel",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Déconnexion réussie",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Déconnexion réussie !")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     *
     * @author CISSE410 <a href="https://github.com/cisse410>#CISSE410</a>
     */

    public function logout(Request $request): JsonResponse {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie !',
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/user",
     *      operationId="currentUser",
     *      tags={"Authentication"},
     *      summary="Obtenir l'utilisateur connecté",
     *      description="Retourne les informations de l'utilisateur actuellement authentifié",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Informations de l'utilisateur",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */

    public function user(Request $request) : JsonResponse {
        return response()->json($request->user());
    }
}
