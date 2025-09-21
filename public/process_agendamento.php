<?php
/**
 * Processamento do Agendamento de Consultas
 * PetAmigo - Sistema de Adoção de Animais
 */

require_once '../includes/db_config.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: agendamento.html');
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
    $nome_adotante = sanitizeInput($_POST['nome_adotante'] ?? '');
    $telefone = sanitizeInput($_POST['telefone'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $data = sanitizeInput($_POST['data'] ?? '');
    $horario = sanitizeInput($_POST['horario'] ?? '');
    $observacoes = sanitizeInput($_POST['observacoes'] ?? '');
    
    // Validações obrigatórias
    if (empty($nome_adotante)) {
        $errors[] = "Nome completo é obrigatório.";
    }
    
    if (empty($telefone)) {
        $errors[] = "Telefone é obrigatório.";
    }
    
    if (empty($email)) {
        $errors[] = "E-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "E-mail inválido.";
    }
    
    if (empty($data)) {
        $errors[] = "Data da visita é obrigatória.";
    } else {
        // Validar se a data não é no passado
        $data_agendamento = new DateTime($data);
        $hoje = new DateTime();
        $hoje->setTime(0, 0, 0);
        
        if ($data_agendamento < $hoje) {
            $errors[] = "A data da visita não pode ser no passado.";
        }
        
        // Validar se não é domingo
        if ($data_agendamento->format('w') == 0) {
            $errors[] = "Não atendemos aos domingos.";
        }
    }
    
    if (empty($horario)) {
        $errors[] = "Horário é obrigatório.";
    } else {
        // Validar horários permitidos
        $horarios_permitidos = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
        if (!in_array($horario, $horarios_permitidos)) {
            $errors[] = "Horário inválido.";
        }
    }
    
    // Verificar se já existe agendamento para a mesma data e horário
    if (empty($errors)) {
        $sql_check = "SELECT COUNT(*) as count FROM consultas WHERE data = ? AND horario = ? AND status = 'agendado'";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$data, $horario]);
        $result_check = $stmt_check->fetch();
        
        if ($result_check['count'] > 0) {
            $errors[] = "Já existe um agendamento para esta data e horário. Escolha outro horário.";
        }
    }
    
    // Se não há erros, inserir no banco de dados
    if (empty($errors)) {
        $sql = "INSERT INTO consultas (nome_adotante, telefone, email, data, horario, observacoes) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([
            $nome_adotante,
            $telefone,
            $email,
            $data,
            $horario,
            $observacoes
        ]);
        
        if ($result) {
            $success = true;
            $consulta_id = $pdo->lastInsertId();
        } else {
            $errors[] = "Erro ao agendar consulta. Tente novamente.";
        }
    }
    
} catch (PDOException $e) {
    error_log("Erro no agendamento: " . $e->getMessage());
    $errors[] = "Erro interno do servidor. Tente novamente mais tarde.";
} catch (Exception $e) {
    error_log("Erro geral no agendamento: " . $e->getMessage());
    $errors[] = "Erro inesperado. Tente novamente.";
}

// Preparar resposta
$response = [
    'success' => $success,
    'errors' => $errors,
    'consulta_id' => $consulta_id ?? null
];

// Se é uma requisição AJAX, retornar JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Caso contrário, redirecionar com mensagem
if ($success) {
    $message = urlencode("Consulta agendada com sucesso! Entraremos em contato para confirmar.");
    header("Location: agendamento.html?success=1&message=" . $message);
} else {
    $error_message = urlencode(implode('; ', $errors));
    header("Location: agendamento.html?error=1&message=" . $error_message);
}
exit;
?>

