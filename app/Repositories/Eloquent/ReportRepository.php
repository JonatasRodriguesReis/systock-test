<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface {
    public function generateReport(int $limit, int $offset)
    {
        $query = "
            WITH user_stats AS (
                SELECT
                    usuario_id,
                    COUNT(id) as total_produtos,
                    ROUND(AVG(preco), 2) as preco_medio,
                    json_agg(
                        json_build_object(
                            'id', id,
                            'nome', nome,
                            'preco', preco
                        )
                    ) FILTER (WHERE id IS NOT NULL) as lista_produtos
                FROM produtos
                GROUP BY usuario_id
                ORDER BY total_produtos DESC
            )
            SELECT
                u.id,
                u.nome,
                u.email,
                COALESCE(s.total_produtos, 0) as total_produtos,
                COALESCE(s.preco_medio, 0) as preco_medio,
                COALESCE(s.lista_produtos, '[]') as produtos
            FROM usuarios u
            LEFT JOIN user_stats s ON s.usuario_id = u.id
            ORDER BY s.total_produtos DESC NULLS LAST
            LIMIT :limit OFFSET :offset
        ";

        $results = DB::select($query, [
            'limit' => $limit,
            'offset' => $offset
        ]);

        // Decode the JSON string of products for each user
        foreach ($results as $row) {
            $row->produtos = json_decode($row->produtos);
        }

        return $results;
    }

    public function generateRankingReport()
    {
        $query = "
            SELECT u.nome AS nome, count(p.id) AS total_produtos
            FROM usuarios u
            JOIN produtos p ON p.usuario_id = u.id
            GROUP BY u.id, u.nome
            ORDER BY total_produtos DESC
            LIMIT 10;
        ";

        $results = DB::select($query);

        return $results;
    }

    public function generatePriceRangeReport()
    {
        $query = "
            SELECT
                CASE
                    WHEN preco < 100 THEN 'Econômico (0-100)'
                    WHEN preco BETWEEN 100 AND 500 THEN 'Médio (100-500)'
                    ELSE 'Premium (>500)'
                END as faixa_preco,
                COUNT(*) as total
            FROM produtos
            GROUP BY faixa_preco
            ORDER BY faixa_preco DESC;
        ";

        $results = DB::select($query);

        return $results;
    }
}
