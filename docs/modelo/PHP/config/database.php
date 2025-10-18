<?php
/**
 * Configuração do banco de dados
 * Arquivo responsável pela conexão com o banco de dados SQLite
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Constantes
    const DB_PATH = __DIR__ . '/../database/app.db';
    const IMPOSTO = 0.10; // Imposto fixo (10%)
    
    /**
     * Construtor privado para implementar Singleton
     */
    private function __construct() {
        if (!file_exists(dirname(self::DB_PATH))) {
            mkdir(dirname(self::DB_PATH), 0777, true);
        }
        
        $this->connection = new SQLite3(self::DB_PATH);
        $this->criarTabelas();
    }
    
    /**
     * Obtém a instância única da conexão
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Retorna a conexão com o banco
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Cria as tabelas se não existirem
     */
    private function criarTabelas() {
        // Tabela de itens do cardápio
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS item (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                preco REAL NOT NULL,
                categoria TEXT DEFAULT "geral"
            )
        ');
        
        // Tabela de mesas
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS mesa (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                numero INTEGER NOT NULL,
                status TEXT DEFAULT "livre",
                reserva_nome TEXT
            )
        ');
        
        // Tabela de pedidos
        $this->connection->exec('
            CREATE TABLE IF NOT EXISTS pedido (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                mesa_id INTEGER NOT NULL,
                itens TEXT NOT NULL,
                observacoes TEXT,
                subtotal REAL NOT NULL,
                imposto REAL NOT NULL,
                total REAL NOT NULL,
                status TEXT DEFAULT "aberto",
                data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }
}
?>