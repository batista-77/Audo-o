<?php
/**
 * API para buscar animais disponíveis
 * PetAmigo - Sistema de Adoção de Animais
 */

require_once '../includes/db_config.php';

// Definir cabeçalhos para API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Conectar ao banco de dados
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Parâmetros de filtro (opcionais)
    $especie = $_GET['especie'] ?? '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    // Construir query
    $sql = "SELECT * FROM animais WHERE status = 'disponivel'";
    $params = [];
    
    if (!empty($especie) && in_array($especie, ['cachorro', 'gato'])) {
        $sql .= " AND especie = ?";
        $params[] = $especie;
    }
    
    $sql .= " ORDER BY data_cadastro DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    // Executar query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $animals = $stmt->fetchAll();
    
    // Processar dados dos animais
    foreach ($animals as &$animal) {
        // Adicionar URL completa da foto
        if ($animal['foto']) {
            $animal['foto_url'] = 'uploads/' . $animal['foto'];
        } else {
            // Foto padrão baseada na espécie
            $animal['foto_url'] = 'images/default-' . $animal['especie'] . '.jpg';
        }
        
        // Formatar data
        $animal['data_cadastro_formatada'] = date('d/m/Y', strtotime($animal['data_cadastro']));
    }
    
    // Contar total de animais disponíveis
    $sql_count = "SELECT COUNT(*) as total FROM animais WHERE status = 'disponivel'";
    if (!empty($especie) && in_array($especie, ['cachorro', 'gato'])) {
        $sql_count .= " AND especie = ?";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->execute([$especie]);
    } else {
        $stmt_count = $pdo->query($sql_count);
    }
    $total = $stmt_count->fetch()['total'];
    
    // Resposta de sucesso
    $response = [
        'success' => true,
        'data' => $animals,
        'total' => $total,
        'limit' => $limit,
        'offset' => $offset
    ];
    
} catch (PDOException $e) {
    error_log("Erro ao buscar animais: " . $e->getMessage());
    $response = [
        'success' => false,
        'error' => 'Erro interno do servidor'
    ];
} catch (Exception $e) {
    error_log("Erro geral ao buscar animais: " . $e->getMessage());
    $response = [
        'success' => false,
        'error' => 'Erro inesperado'
    ];
}

echo json_encode($response);
?>

