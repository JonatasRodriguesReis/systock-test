<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface {
    public function list(array $params);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
