<?php
/**
 * Página Administrativa - Consultas Agendadas
 * PetAmigo - Sistema de Adoção de Animais
 */

require_once '../includes/db_config.php';

try {
    // Conectar ao banco de dados
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Buscar consultas
    $sql = "SELECT * FROM consultas ORDER BY data DESC, horario ASC";
    $stmt = $pdo->query($sql);
    $consultas = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Erro ao buscar consultas: " . $e->getMessage());
    $consultas = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Agendadas - PetAmigo Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .admin-header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .consultas-table {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px var(--shadow);
        }

        .table-header {
            background-color: var(--secondary-color);
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table-header h2 {
            margin: 0;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--background-light);
            font-weight: 600;
            color: var(--text-dark);
        }

        tr:hover {
            background-color: var(--background-light);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-agendado {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-realizado {
            background-color: #e8f5e8;
            color: #388e3c;
        }

        .status-cancelado {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .admin-container {
                padding: 0 1rem;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.5rem;
            }

            .consultas-table {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="index.html" class="back-link">
            <i class="fas fa-arrow-left"></i> Voltar ao Site
        </a>

        <div class="admin-header">
            <h1><i class="fas fa-calendar-alt"></i> Painel Administrativo</h1>
            <p>Consultas Agendadas - PetAmigo</p>
        </div>

        <div class="consultas-table">
            <div class="table-header">
                <h2>Consultas Agendadas (<?php echo count($consultas); ?>)</h2>
            </div>

            <?php if (empty($consultas)): ?>
                <div class="no-data">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                    <p>Nenhuma consulta agendada ainda.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Agendado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $consulta): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($consulta['id']); ?></td>
                                <td><?php echo htmlspecialchars($consulta['nome_adotante']); ?></td>
                                <td><?php echo htmlspecialchars($consulta['telefone']); ?></td>
                                <td><?php echo htmlspecialchars($consulta['email']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($consulta['data'])); ?></td>
                                <td><?php echo date('H:i', strtotime($consulta['horario'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $consulta['status']; ?>">
                                        <?php echo ucfirst($consulta['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($consulta['data_agendamento'])); ?></td>
                            </tr>
                            <?php if (!empty($consulta['observacoes'])): ?>
                                <tr>
                                    <td colspan="8" style="background-color: var(--background-light); font-style: italic; padding-left: 2rem;">
                                        <strong>Observações:</strong> <?php echo htmlspecialchars($consulta['observacoes']); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

