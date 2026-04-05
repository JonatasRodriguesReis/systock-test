<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ProductService {
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function create(array $data) {
        return $this->productRepository->create($data);
    }

    public function listAll(array $params) {
        return $this->productRepository->list($params);
    }

    public function update(int $id, array $data) {
        return $this->productRepository->update($id, $data);
    }

    public function findById(int $id) {
        return $this->productRepository->find($id);
    }

    public function delete(int $id) { return $this->productRepository->delete($id); }
}
