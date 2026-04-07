<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'senha' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            // Verificação de credenciais
            if (! $user || ! Hash::check($request->senha, $user->senha)) {
                // Erro 401 em APIs é mais semântico para falha de login do que 422
                return response()->json([
                    'error' => 'Credenciais inválidas.',
                    'message' => 'E-mail ou senha não conferem.'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ]);

        } catch (ValidationException $e) {
            // Retorna os erros de validação do Laravel (e-mail mal formatado, campos vazios)
            return response()->json([
                'error' => 'Dados inválidos.',
                'messages' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao processar o login.'], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout realizado com sucesso']);

        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao encerrar sessão.'], 500);
        }
    }

    public function me(Request $request)
    {
        try{
            return new UserResource($request->user()->load('produtos'));
        }catch (\Exception $e) {
            Log::error('Auth Me Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao recuperar dados do perfil.'], 500);
        }
    }
}
