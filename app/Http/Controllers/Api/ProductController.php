<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller {
    public function __construct(protected ProductService $productService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort_by', 'sort_order','per_page']);
        $products = $this->productService->listAll($filters);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        Log::info('Creating a new product', $request->validated());

        $product = $this->productService->create($request->validated());

        return response()->json($product, 201);
    }

    public function show(int $id)
    {
        $product = $this->productService->findById($id);
        return ProductResource::collection($product);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->update($id, $request->all());
        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->delete($id);
        return response()->json(null, 204);
    }
}
