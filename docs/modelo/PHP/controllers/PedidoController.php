<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Mesa.php';
require_once __DIR__ . '/../config/helpers.php';

/**
 * Controlador para gerenciar pedidos
 */
class PedidoController {
    private $pedidoModel;
    private $mesaModel;
    
    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->mesaModel = new Mesa();
    }
    
    /**
     * Processa as requisições para pedidos
     */
    public function processar($metodo, $params = []) {
        switch ($metodo) {
            case 'GET':
                return $this->listar();
            case 'POST':
                return $this->criar();
            case 'PUT':
                if (isset($params[0])) {
                    return $this->atualizarStatus($params[0]);
                }
                responderJSON(['erro' => 'ID do pedido não fornecido'], 400);
                break;
            default:
                responderJSON(['erro' => 'Método não permitido'], 405);
        }
    }
    
    /**
     * Lista todos os pedidos
     */
    private function listar() {
        $pedidos = $this->pedidoModel->listarTodos();
        responderJSON($pedidos);
    }
    
    /**
     * Cria um novo pedido
     */
    private function criar() {
        $dados = obterDadosRequisicao();
        
        // Validação básica
        if (empty($dados['mesa_id']) || empty($dados['itens'])) {
            responderJSON(['erro' => 'Mesa e itens são obrigatórios'], 400);
        }
        
        // Atualiza status da mesa
        $this->mesaModel->atualizarStatus($dados['mesa_id'], 'ocupada');
        
        // Cria o pedido
        $observacoes = isset($dados['observacoes']) ? $dados['observacoes'] : '';
        $id = $this->pedidoModel->criar($dados['mesa_id'], $dados['itens'], $observacoes);
        
        responderJSON([
            'id' => $id,
            'mensagem' => 'Pedido criado com sucesso'
        ], 201);
    }
    
    /**
     * Atualiza o status de um pedido
     */
    private function atualizarStatus($id) {
        $dados = obterDadosRequisicao();
        
        if (empty($dados['status'])) {
            responderJSON(['erro' => 'Status é obrigatório'], 400);
        }
        
        $sucesso = $this->pedidoModel->atualizarStatus($id, $dados['status']);
        
        if ($sucesso) {
            responderJSON(['mensagem' => 'Status atualizado com sucesso']);
        } else {
            responderJSON(['erro' => 'Pedido não encontrado'], 404);
        }
    }
}
?>