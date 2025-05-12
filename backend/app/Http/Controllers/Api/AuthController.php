<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     * @param Illuminate\Http\Request $request
     * @return Illuminte\Http\JsonResponse
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
     * Login a user
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     * @throws Illuminate\Validation\ValidationException
     *
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
     * Logout a user that's mean revoke the token
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
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
     * Recuperer l'utilisateur connecté
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     *
     * @author CISSE410 <a href="https://github.com/cisse410">#CISSE410</a>
     */
    public function user(Request $request) : JsonResponse {
        return response()->json($request->user());
    }
}
