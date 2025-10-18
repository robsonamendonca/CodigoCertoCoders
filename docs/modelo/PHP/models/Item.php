<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Modelo para gerenciar itens do cardápio
 */
class Item {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Lista todos os itens do cardápio
     */
    public function listarTodos() {
        $resultado = $this->db->query('SELECT id, nome, preco, categoria FROM item');
        
        $itens = [];
        while ($item = $resultado->fetchArray(SQLITE3_ASSOC)) {
            $itens[] = $item;
        }
        
        return $itens;
    }
    
    /**
     * Adiciona um novo item ao cardápio
     */
    public function adicionar($nome, $preco, $categoria = 'geral') {
        $stmt = $this->db->prepare('INSERT INTO item (nome, preco, categoria) VALUES (:nome, :preco, :categoria)');
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $stmt->bindValue(':preco', $preco, SQLITE3_FLOAT);
        $stmt->bindValue(':categoria', $categoria, SQLITE3_TEXT);
        $stmt->execute();
        
        return $this->db->lastInsertRowID();
    }
    
    /**
     * Busca um item pelo nome
     */
    public function buscarPorNome($nome) {
        $stmt = $this->db->prepare('SELECT * FROM item WHERE nome = :nome');
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $resultado = $stmt->execute();
        
        return $resultado->fetchArray(SQLITE3_ASSOC);
    }
}
?>