<?php
/**
 * API para buscar um animal específico
 * PetAmigo - Sistema de Adoção de Animais
 */

require_once '../includes/db_config.php';

// Definir cabeçalhos para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Verificar se o ID foi fornecido
    $id = $_GET['id'] ?? '';
    
    if (empty($id) || !is_numeric($id)) {
        throw new Exception('ID do animal é obrigatório e deve ser numérico');
    }
    
    // Conectar ao banco de dados
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Buscar animal
    $sql = "SELECT * FROM animais WHERE id = ? AND status = 'disponivel'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $animal = $stmt->fetch();
    
    if (!$animal) {
        throw new Exception('Animal não encontrado ou não disponível');
    }
    
    // Processar dados do animal
    if ($animal['foto']) {
        $animal['foto_url'] = 'uploads/' . $animal['foto'];
    } else {
        // Foto padrão baseada na espécie
        $animal['foto_url'] = 'images/default-' . $animal['especie'] . '.jpg';
    }
    
    // Formatar data
    $animal['data_cadastro_formatada'] = date('d/m/Y', strtotime($animal['data_cadastro']));
    
    // Resposta de sucesso
    $response = [
        'success' => true,
        'data' => $animal
    ];
    
} catch (PDOException $e) {
    error_log("Erro ao buscar animal: " . $e->getMessage());
    $response = [
        'success' => false,
        'error' => 'Erro interno do servidor'
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

echo json_encode($response);
?>

