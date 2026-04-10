/*
Query A: Listar os usuários com mais produtos (TOP 10)
*/
SELECT u.nome AS nome, count(p.id) AS total_produtos
FROM usuarios u
JOIN produtos p ON p.usuario_id = u.id
GROUP BY u.id, u.nome
ORDER BY total_produtos DESC
LIMIT 10;

/*
Query B: Buscar o produto mais caro por usuário
*/
SELECT DISTINCT ON (usuario_id)
    usuario_id,
    nome as produto_mais_caro,
    preco
FROM produtos
ORDER BY usuario_id, preco DESC;

/*
Query C: Exibir a quantidade de produtos por faixa de preço
*/
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


