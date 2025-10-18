<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para gerenciar pedidos
 */
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Cria um novo pedido
     */
    public function criar($mesaId, $itens, $observacoes = '') {
        // Calcula valores
        $subtotal = 0;
        foreach ($itens as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        
        $imposto = $subtotal * Database::IMPOSTO;
        $total = $subtotal + $imposto;
        
        // Insere o pedido
        $stmt = $this->db->prepare('
            INSERT INTO pedido (mesa_id, itens, observacoes, subtotal, imposto, total, status) 
            VALUES (:mesa_id, :itens, :observacoes, :subtotal, :imposto, :total, "aberto")
        ');
        
        $stmt->bindValue(':mesa_id', $mesaId, SQLITE3_INTEGER);
        $stmt->bindValue(':itens', json_encode($itens), SQLITE3_TEXT);
        $stmt->bindValue(':observacoes', $observacoes, SQLITE3_TEXT);
        $stmt->bindValue(':subtotal', $subtotal, SQLITE3_FLOAT);
        $stmt->bindValue(':imposto', $imposto, SQLITE3_FLOAT);
        $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
        $stmt->execute();
        
        return $this->db->lastInsertRowID();
    }
    
    /**
     * Lista todos os pedidos
     */
    public function listarTodos() {
        $resultado = $this->db->query('
            SELECT p.*, m.numero as mesa_numero 
            FROM pedido p
            JOIN mesa m ON p.mesa_id = m.id
            ORDER BY p.data_criacao DESC
        ');
        
        $pedidos = [];
        while ($pedido = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $pedido['itens'] = json_decode($pedido['itens'], true);
            $pedidos[] = $pedido;
        }
        
        return $pedidos;
    }
    
    /**
     * Atualiza o status de um pedido
     */
    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare('UPDATE pedido SET status = :status WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->execute();
        
        return $stmt->changes() > 0;
    }
}
?>