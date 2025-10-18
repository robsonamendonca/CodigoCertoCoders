<?php
require_once __DIR__ . '/../models/Mesa.php';
require_once __DIR__ . '/../config/helpers.php';

/**
 * Controlador para gerenciar mesas
 */
class MesaController {
    private $mesaModel;
    
    public function __construct() {
        $this->mesaModel = new Mesa();
    }
    
    /**
     * Processa as requisições para mesas
     */
    public function processar($metodo, $params = []) {
        switch ($metodo) {
            case 'GET':
                return $this->listar();
            case 'POST':
                return $this->adicionar();
            case 'PUT':
                if (isset($params[0])) {
                    return $this->atualizarStatus($params[0]);
                }
                responderJSON(['erro' => 'ID da mesa não fornecido'], 400);
                break;
            default:
                responderJSON(['erro' => 'Método não permitido'], 405);
        }
    }
    
    /**
     * Lista todas as mesas
     */
    private function listar() {
        $mesas = $this->mesaModel->listarTodas();
        responderJSON($mesas);
    }
    
    /**
     * Adiciona uma nova mesa
     */
    private function adicionar() {
        $dados = obterDadosRequisicao();
        
        // Validação básica
        if (empty($dados['numero'])) {
            responderJSON(['erro' => 'Número da mesa é obrigatório'], 400);
        }
        
        $id = $this->mesaModel->adicionar($dados['numero']);
        
        responderJSON([
            'id' => $id,
            'numero' => $dados['numero'],
            'status' => 'livre',
            'mensagem' => 'Mesa adicionada com sucesso'
        ], 201);
    }
    
    /**
     * Atualiza o status de uma mesa
     */
    private function atualizarStatus($id) {
        $dados = obterDadosRequisicao();
        
        if (empty($dados['status'])) {
            responderJSON(['erro' => 'Status é obrigatório'], 400);
        }
        
        $reservaNome = isset($dados['reserva_nome']) ? $dados['reserva_nome'] : null;
        $sucesso = $this->mesaModel->atualizarStatus($id, $dados['status'], $reservaNome);
        
        if ($sucesso) {
            responderJSON(['mensagem' => 'Status atualizado com sucesso']);
        } else {
            responderJSON(['erro' => 'Mesa não encontrada'], 404);
        }
    }
}
?>