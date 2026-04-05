<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;


/**
 * TODO: Filters, pagination, search, sorting, caching and other query to allow the frontend to be more flexible and efficient.
 *
**/

class UserRepository implements UserRepositoryInterface {
    public function list(array $params) {
        $query = User::query()->with('produtos');

        // 1. Search (Busca global por nome, email ou cpf)
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%")
                ->orWhere('cpf', 'like', "%{$search}%");
            });
            //dd($query->toSql(), $query->getBindings());
        }

        // 2. Sorting (Ordenação dinâmica)
        $sortField = $params['sort_by'] ?? 'created_at';
        $sortOrder = $params['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        // 3. Pagination (O Laravel cuida dos offsets e limits)
        $perPage = $params['per_page'] ?? 10;

        return $query->paginate($perPage);
    }
    public function find(int $id) {
        return User::with('produtos')->findOrFail($id);
    }
    public function create(array $data) { return User::create($data); }
    public function update(int $id, array $data) {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }
    public function delete(int $id) { return User::destroy($id); }
}
