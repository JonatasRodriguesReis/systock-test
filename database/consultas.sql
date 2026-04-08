/*
Query A: Listar os usuários com mais produtos (TOP 10)
*/
SELECT u.name, count(p.id) as qtd
FROM users u
JOIN products p ON p.usuario_id = u.id
GROUP BY u.id, u.name
ORDER BY qtd DESC
LIMIT 10;

/*
Query B: Buscar o produto mais caro por usuário
*/
SELECT DISTINCT ON (usuario_id)
    usuario_id,
    nome as produto_mais_caro,
    preco
FROM products
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
FROM products
GROUP BY faixa_preco
ORDER BY total DESC;


