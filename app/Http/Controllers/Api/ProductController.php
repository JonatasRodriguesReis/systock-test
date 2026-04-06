<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller {
    public function __construct(protected ProductService $productService) {}

    public function index(Request $request)
    {
        $usuarioId = $request->user()->id;
        $filters = $request->only(['search', 'sort_by', 'sort_order','per_page']);
        $products = $this->productService->listAll($filters);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return response()->json($product, 201);
    }

    public function show(int $id)
    {
        $product = $this->productService->findById($id);

        return new ProductResource($product);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        $this->authorize('update', $product); // Verifies if the authenticated user can update this product
        $product = $this->productService->update($id, $request->all());
        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        $this->authorize('delete', $product); // Verifies if the authenticated user can delete this product
        $this->productService->delete($id);
        return response()->json(null, 204);
    }
}
