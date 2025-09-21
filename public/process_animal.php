<?php
/**
 * Processamento do Cadastro de Animais
 * PetAmigo - Sistema de Adoção de Animais
 */

require_once '../includes/db_config.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro.html');
    exit;
}

// Inicializar variáveis
$errors = [];
$success = false;

try {
    // Conectar ao banco de dados
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Validar e sanitizar dados
    $nome = sanitizeInput($_POST['nome'] ?? '');
    $especie = sanitizeInput($_POST['especie'] ?? '');
    $idade = sanitizeInput($_POST['idade'] ?? '');
    $raca = sanitizeInput($_POST['raca'] ?? '');
    $pelagem = sanitizeInput($_POST['pelagem'] ?? '');
    $temperamento = sanitizeInput($_POST['temperamento'] ?? '');
    $descricao = sanitizeInput($_POST['descricao'] ?? '');
    
    // Validações obrigatórias
    if (empty($nome)) {
        $errors[] = "Nome do animal é obrigatório.";
    }
    
    if (empty($especie) || !in_array($especie, ['cachorro', 'gato'])) {
        $errors[] = "Espécie é obrigatória e deve ser 'cachorro' ou 'gato'.";
    }
    
    // Processar upload da foto
    $foto_filename = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_result = uploadFile($_FILES['foto'], 'animal_');
        if ($upload_result['success']) {
            $foto_filename = $upload_result['filename'];
        } else {
            $errors = array_merge($errors, $upload_result['errors']);
        }
    }
    
    // Se não há erros, inserir no banco de dados
    if (empty($errors)) {
        $sql = "INSERT INTO animais (nome, especie, idade, raca, pelagem, temperamento, descricao, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([
            $nome,
            $especie,
            $idade,
            $raca,
            $pelagem,
            $temperamento,
            $descricao,
            $foto_filename
        ]);
        
        if ($result) {
            $success = true;
            $animal_id = $pdo->lastInsertId();
        } else {
            $errors[] = "Erro ao cadastrar animal. Tente novamente.";
        }
    }
    
} catch (PDOException $e) {
    error_log("Erro no cadastro de animal: " . $e->getMessage());
    $errors[] = "Erro interno do servidor. Tente novamente mais tarde.";
} catch (Exception $e) {
    error_log("Erro geral no cadastro: " . $e->getMessage());
    $errors[] = "Erro inesperado. Tente novamente.";
}

// Preparar resposta
$response = [
    'success' => $success,
    'errors' => $errors,
    'animal_id' => $animal_id ?? null
];

// Se é uma requisição AJAX, retornar JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Caso contrário, redirecionar com mensagem
if ($success) {
    $message = urlencode("Animal cadastrado com sucesso!");
    header("Location: cadastro.html?success=1&message=" . $message);
} else {
    $error_message = urlencode(implode('; ', $errors));
    header("Location: cadastro.html?error=1&message=" . $error_message);
}
exit;
?>

