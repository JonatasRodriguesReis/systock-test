<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'preco' => (float) $this->preco,
            'preco_formatado' => 'R$ ' . number_format($this->preco, 2, ',', '.'),
            'descricao' => $this->descricao,
            'usuario_id' => $this->usuario_id,
            'dono' => UserResource::make($this->whenLoaded('usuario')),
            'criado_em' => $this->created_at->format('d/m/Y H:i'),
            'total_produtos' => $this->whenLoaded('produtos', function() {
                return $this->produtos->count();
            }),
        ];
    }
}
