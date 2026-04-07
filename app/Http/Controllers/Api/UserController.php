<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller {
    public function __construct(protected UserService $userService) {}

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'sort_by', 'sort_order', 'per_page']);
            $users = $this->userService->listAll($filters);

            return UserResource::collection($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['error' => 'Falha ao listar usuários.'], 500);
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create($request->validated());

            return response()->json(new UserResource($user), 201);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao criar usuário.'], 500);
        }
    }

    public function show(int $id)
    {
        try{
            $user = $this->userService->findById($id);
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Acesso negado ao perfil deste usuário.'], 403);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar dados do usuário.'], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try{
            $user = $this->userService->update($id, $request->all());
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado para atualização.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Você não tem permissão para alterar este usuário.'], 403);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao atualizar usuário.'], 500);
        }
    }

    /**
     * Deletar um usuário
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado para exclusão.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Você não tem permissão para excluir este usuário.'], 403);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao tentar excluir usuário.'], 500);
        }
    }
}
