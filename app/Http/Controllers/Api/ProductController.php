<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class ProductController extends Controller {
    public function __construct(protected ProductService $productService) {}

    public function index(Request $request)
    {
        try{
            $filters = $request->only(['search', 'sort_by', 'sort_order','per_page']);
            $products = $this->productService->listAll($filters);
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products: ' . $e->getMessage()], 500);
        }
    }

    public function myProducts(Request $request)
    {
        try{
            $filters = $request->only(['search', 'sort_by', 'sort_order','per_page']);
            $userId = $request->user()->id;
            $products = $this->productService->listMyProducts($filters, $userId);
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products: ' . $e->getMessage()], 500);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try{
            $product = $this->productService->create($request->validated());
            return response()->json($product, 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try{
            $product = $this->productService->findById($id);
            return new ProductResource($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Acesso negado a este produto.'], 403);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar detalhes do produto.'], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->findById($id);

            $this->authorize('update', $product);

            $updatedProduct = $this->productService->update($id, $request->all());

            return response()->json($updatedProduct);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);

        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Você não tem permissão para editar este produto.'], 403);

        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao atualizar o produto.'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try{
            $product = $this->productService->findById($id);
            $this->authorize('delete', $product); // Verifies if the authenticated user can delete this product
            $this->productService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Produto não encontrado para exclusão.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Você não tem permissão para excluir este produto.'], 403);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao tentar excluir o produto.'], 500);
        }
    }


}
