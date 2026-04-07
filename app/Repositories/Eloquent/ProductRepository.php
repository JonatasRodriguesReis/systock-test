<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductRepositoryInterface {
    public function list(array $params) {
        $query = Product::query()->with('usuario');

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                  ->orWhere('descricao', 'ilike', "%{$search}%");
            });
        }

        return $query->orderBy($params['sort_by'] ?? 'created_at', $params['sort_order'] ?? 'desc')
                     ->paginate($params['per_page'] ?? 10);
    }

    public function listMyProducts(array $params, int $userId) {
        $query = Product::query()->with('usuario');

        $query->where('usuario_id', $userId);

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                  ->orWhere('descricao', 'ilike', "%{$search}%");
            });
        }

        return $query->orderBy($params['sort_by'] ?? 'created_at', $params['sort_order'] ?? 'desc')
                     ->paginate($params['per_page'] ?? 10);
    }

    public function create(array $data) {
        return Product::create($data);
    }

    public function find(int $id) {
        Log::info('Message');
        $query = Product::query()->with('usuario');
        //$query->where('usuario_id', $usuarioId);

        return $query->findOrFail($id);
    }

    public function update(int $id, array $data) {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id) {
        return Product::destroy($id);
    }
}
