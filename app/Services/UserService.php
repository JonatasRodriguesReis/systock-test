<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function create(array $data) {
        // Lógica de negócio: Hash da senha
        $data['senha'] = Hash::make($data['senha']);
        return $this->userRepository->create($data);
    }

    public function listAll(array $params) {
        return $this->userRepository->list($params);
    }

    public function update(int $id, array $data) {
        if (isset($data['senha'])) {
            $data['senha'] = Hash::make($data['senha']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function findById(int $id) {
        return $this->userRepository->find($id);
    }

    public function delete(int $id) { return $this->userRepository->delete($id); }
}
