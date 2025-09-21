<?php
/**
 * Configuração do Banco de Dados
 * PetAmigo - Sistema de Adoção de Animais
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'petamigo_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações de upload
define('UPLOAD_DIR', '../public/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

/**
 * Classe para conexão com o banco de dados
 */
class Database {
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    /**
     * Estabelece conexão com o banco de dados
     */
    private function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    /**
     * Retorna a conexão PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Cria as tabelas necessárias se não existirem
     */
    public function createTables() {
        try {
            // Tabela de animais
            $sql_animais = "
                CREATE TABLE IF NOT EXISTS animais (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nome VARCHAR(100) NOT NULL,
                    especie ENUM('cachorro', 'gato') NOT NULL,
                    idade VARCHAR(50),
                    raca VARCHAR(100),
                    pelagem VARCHAR(100),
                    temperamento VARCHAR(200),
                    descricao TEXT,
                    foto VARCHAR(255),
                    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    status ENUM('disponivel', 'adotado') DEFAULT 'disponivel'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            // Tabela de consultas/agendamentos
            $sql_consultas = "
                CREATE TABLE IF NOT EXISTS consultas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nome_adotante VARCHAR(150) NOT NULL,
                    telefone VARCHAR(20) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    data DATE NOT NULL,
                    horario TIME NOT NULL,
                    observacoes TEXT,
                    data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    status ENUM('agendado', 'realizado', 'cancelado') DEFAULT 'agendado'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            $this->connection->exec($sql_animais);
            $this->connection->exec($sql_consultas);
            
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao criar tabelas: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Insere dados de exemplo se as tabelas estiverem vazias
     */
    public function insertSampleData() {
        try {
            // Verificar se já existem dados
            $stmt = $this->connection->query("SELECT COUNT(*) as count FROM animais");
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                // Inserir animais de exemplo
                $sample_animals = [
                    [
                        'nome' => 'Rex',
                        'especie' => 'cachorro',
                        'idade' => '2 anos',
                        'raca' => 'Vira-lata',
                        'pelagem' => 'Curta',
                        'temperamento' => 'Amigável, Brincalhão',
                        'descricao' => 'Rex é um cachorro muito carinhoso e brincalhão. Adora crianças e se dá bem com outros animais. Está procurando uma família que possa dar muito amor e carinho.',
                        'foto' => 'sample-dog.jpg'
                    ],
                    [
                        'nome' => 'Luna',
                        'especie' => 'gato',
                        'idade' => '1 ano',
                        'raca' => 'Siamês',
                        'pelagem' => 'Curta',
                        'temperamento' => 'Tímida, Carinhosa',
                        'descricao' => 'Luna é uma gatinha muito doce, mas um pouco tímida no início. Quando se acostuma, é extremamente carinhosa e ronrona muito. Ideal para um lar tranquilo.',
                        'foto' => 'sample-cat.jpg'
                    ],
                    [
                        'nome' => 'Bella',
                        'especie' => 'cachorro',
                        'idade' => '3 anos',
                        'raca' => 'Labrador',
                        'pelagem' => 'Média',
                        'temperamento' => 'Dócil, Protetora',
                        'descricao' => 'Bella é uma cadela muito inteligente e leal. É ótima com crianças e tem instinto protetor. Precisa de exercícios regulares e muito carinho.',
                        'foto' => 'sample-dog2.jpg'
                    ]
                ];
                
                $sql = "INSERT INTO animais (nome, especie, idade, raca, pelagem, temperamento, descricao, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);
                
                foreach ($sample_animals as $animal) {
                    $stmt->execute([
                        $animal['nome'],
                        $animal['especie'],
                        $animal['idade'],
                        $animal['raca'],
                        $animal['pelagem'],
                        $animal['temperamento'],
                        $animal['descricao'],
                        $animal['foto']
                    ]);
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir dados de exemplo: " . $e->getMessage());
            return false;
        }
    }
}

/**
 * Função para sanitizar dados de entrada
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Função para validar upload de arquivo
 */
function validateFileUpload($file) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Erro no upload do arquivo.";
        return $errors;
    }
    
    // Verificar tamanho
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = "Arquivo muito grande. Tamanho máximo: 5MB.";
    }
    
    // Verificar tipo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        $errors[] = "Tipo de arquivo não permitido. Use JPG, PNG ou GIF.";
    }
    
    return $errors;
}

/**
 * Função para fazer upload de arquivo
 */
function uploadFile($file, $prefix = 'animal_') {
    $errors = validateFileUpload($file);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Gerar nome único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . uniqid() . '.' . $extension;
    $filepath = UPLOAD_DIR . $filename;
    
    // Criar diretório se não existir
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'errors' => ['Erro ao salvar arquivo.']];
    }
}

// Inicializar banco de dados
try {
    $db = new Database();
    $db->createTables();
    $db->insertSampleData();
} catch (Exception $e) {
    error_log("Erro na inicialização do banco: " . $e->getMessage());
}
?>

