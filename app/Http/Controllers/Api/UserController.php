<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
    public function __construct(protected UserService $userService) {}

    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        // Captura filtros da URL: ?search=jon&sort_by=name&per_page=5
        $filters = $request->only(['search', 'sort_by', 'sort_order','per_page']);
        $users = $this->userService->listAll($filters);
        return UserResource::collection($users);
    }

    /**
     * Criar um novo usuário
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        Log::info('Creating a new user', $request->validated());

        // O $request->validated() só retorna os dados que passaram nas suas Rules (CPF, Email, etc)
        $user = $this->userService->create($request->validated());

        return response()->json($user, 201);
    }

    /**
     * Exibir um usuário específico
     */
    public function show(int $id)
    {
        $user = $this->userService->findById($id);
        return new UserResource($user);
    }

    /**
     * Atualizar um usuário
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Aqui você poderia criar um UpdateUserRequest específico se as regras mudarem
        $user = $this->userService->update($id, $request->all());
        return response()->json($user);
    }

    /**
     * Deletar um usuário
     */
    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json(null, 204);
    }
}
