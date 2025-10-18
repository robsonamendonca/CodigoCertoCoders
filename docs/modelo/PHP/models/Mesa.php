<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para gerenciar mesas do restaurante
 */
class Mesa {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Lista todas as mesas
     */
    public function listarTodas() {
        $resultado = $this->db->query('SELECT id, numero, status, reserva_nome FROM mesa');
        
        $mesas = [];
        while ($mesa = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $mesas[] = $mesa;
        }
        
        return $mesas;
    }
    
    /**
     * Adiciona uma nova mesa
     */
    public function adicionar($numero) {
        $stmt = $this->db->prepare('INSERT INTO mesa (numero, status) VALUES (:numero, "livre")');
        $stmt->bindValue(':numero', $numero, SQLITE3_INTEGER);
        $stmt->execute();
        
        return $this->db->lastInsertRowID();
    }
    
    /**
     * Atualiza o status de uma mesa
     */
    public function atualizarStatus($id, $status, $reservaNome = null) {
        $stmt = $this->db->prepare('UPDATE mesa SET status = :status, reserva_nome = :reserva_nome WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':reserva_nome', $reservaNome, SQLITE3_TEXT);
        $stmt->execute();
        
        return $stmt->changes() > 0;
    }
}
?>