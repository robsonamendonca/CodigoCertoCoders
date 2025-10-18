<?php
/**
 * Arquivo principal da aplicação
 * Gerencia as rotas e direciona para os controladores apropriados
 */

// Carrega os controladores
require_once __DIR__ . '/../controllers/CardapioController.php';
require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/MesaController.php';

// Define cabeçalhos para CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Se for requisição OPTIONS (preflight), retorna apenas os cabeçalhos
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Obtém a URI da requisição
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/';  // Ajuste conforme necessário

// Remove o caminho base e parâmetros de consulta
$uri = str_replace($basePath, '', $requestUri);
$uri = parse_url($uri, PHP_URL_PATH);

// Divide a URI em partes
$uriParts = explode('/', trim($uri, '/'));

// Determina o controlador com base na primeira parte da URI
$controller = isset($uriParts[0]) ? $uriParts[0] : '';

// Remove o controlador da lista de parâmetros
array_shift($uriParts);

// Obtém o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Roteamento para os controladores
switch ($controller) {
    case 'api':
        // Determina o recurso da API
        $recurso = isset($uriParts[0]) ? $uriParts[0] : '';
        array_shift($uriParts);
        
        switch ($recurso) {
            case 'cardapio':
                $cardapioController = new CardapioController();
                $cardapioController->processar($method, $uriParts);
                break;
                
            case 'pedido':
                $pedidoController = new PedidoController();
                $pedidoController->processar($method, $uriParts);
                break;
                
            case 'mesa':
                $mesaController = new MesaController();
                $mesaController->processar($method, $uriParts);
                break;
                
            default:
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['erro' => 'Recurso não encontrado']);
                break;
        }
        break;
        
    case '':
        // Página inicial - carrega o frontend
        include __DIR__ . '/../views/index.html';
        break;
        
    default:
        // Tenta carregar um arquivo estático
        $filePath = __DIR__ . '/' . $uri;
        
        if (file_exists($filePath) && !is_dir($filePath)) {
            // Define o tipo de conteúdo com base na extensão
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'css':
                    header('Content-Type: text/css');
                    break;
                case 'js':
                    header('Content-Type: application/javascript');
                    break;
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
            }
            
            readfile($filePath);
        } else {
            // Página não encontrada
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['erro' => 'Página não encontrada']);
        }
        break;
}
?>