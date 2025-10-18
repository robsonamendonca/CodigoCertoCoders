<?php
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../config/helpers.php';

/**
 * Controlador para gerenciar o cardápio
 */
class CardapioController {
    private $itemModel;
    
    public function __construct() {
        $this->itemModel = new Item();
    }
    
    /**
     * Processa as requisições para o cardápio
     */
    public function processar($metodo, $params = []) {
        switch ($metodo) {
            case 'GET':
                return $this->listar();
            case 'POST':
                return $this->adicionar();
            default:
                responderJSON(['erro' => 'Método não permitido'], 405);
        }
    }
    
    /**
     * Lista todos os itens do cardápio
     */
    private function listar() {
        $itens = $this->itemModel->listarTodos();
        responderJSON($itens);
    }
    
    /**
     * Adiciona um novo item ao cardápio
     */
    private function adicionar() {
        $dados = obterDadosRequisicao();
        
        // Validação básica
        if (empty($dados['nome']) || !isset($dados['preco'])) {
            responderJSON(['erro' => 'Nome e preço são obrigatórios'], 400);
        }
        
        // Verifica se já existe item com este nome
        if ($this->itemModel->buscarPorNome($dados['nome'])) {
            responderJSON(['erro' => 'Item já existe no cardápio'], 409);
        }
        
        $categoria = isset($dados['categoria']) ? $dados['categoria'] : 'geral';
        $id = $this->itemModel->adicionar($dados['nome'], $dados['preco'], $categoria);
        
        responderJSON([
            'id' => $id,
            'nome' => $dados['nome'],
            'preco' => $dados['preco'],
            'categoria' => $categoria
        ], 201);
    }
}
?>