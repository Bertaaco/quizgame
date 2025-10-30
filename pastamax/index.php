<?php
// Inicia a sessão
session_start();

// Habilita exibição de erros (ajuda no localhost)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtém o parâmetro "route" enviado pela URL (ex: ?route=game)
$route = $_GET['route'] ?? 'start';

// Define o script que será carregado
switch ($route) {
    case 'start':
        $script = 'start';
        break;
    case 'game':
        $script = 'game';
        break;
    case 'gameover':
        $script = 'gameover';
        break;
    default:
        $script = '404';
        break;
}

// Caminho do arquivo de dados
$dataFile = __DIR__ . '/data/capitals.php';

// Garante que o arquivo existe antes de carregar
if (file_exists($dataFile)) {
    $capitals = require $dataFile;
} else {
    $capitals = [];
}

// Inclui o cabeçalho
require_once __DIR__ . '/scripts/header.php';

// Verifica se o script de conteúdo existe antes de incluir
$scriptPath = __DIR__ . "/scripts/{$script}.php";

if (file_exists($scriptPath)) {
    require_once $scriptPath;
} else {
    echo "<p>Erro: O arquivo <strong>{$script}.php</strong> não foi encontrado.</p>";
}

// Inclui o rodapé
require_once __DIR__ . '/scripts/footer.php';