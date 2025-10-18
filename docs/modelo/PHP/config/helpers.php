<?php
/**
 * Funções auxiliares para o sistema
 */

/**
 * Retorna resposta em formato JSON
 */
function responderJSON($dados, $codigo = 200) {
    header('Content-Type: application/json');
    http_response_code($codigo);
    echo json_encode($dados);
    exit;
}

/**
 * Obtém dados do corpo da requisição
 */
function obterDadosRequisicao() {
    return json_decode(file_get_contents('php://input'), true);
}
?>