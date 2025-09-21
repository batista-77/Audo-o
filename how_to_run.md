# Como Rodar o Projeto PetAmigo Localmente

Este guia detalha os passos necessários para configurar e executar o projeto PetAmigo na sua máquina local. Certifique-se de ter os pré-requisitos instalados antes de começar.

## 🚀 Pré-requisitos

Para rodar este projeto, você precisará ter instalado:

1.  **Servidor Web com PHP:** Recomendamos o uso do PHP Built-in Server para desenvolvimento local, mas você pode usar Apache ou Nginx se preferir.
    *   **PHP 8.1 ou superior**
    *   Extensão `php-mysql` (geralmente incluída ou fácil de instalar)
2.  **Servidor de Banco de Dados MySQL:**
    *   **MySQL 5.7 ou superior**

### Como instalar no Ubuntu/Debian (Exemplo)

```bash
sudo apt update
sudo apt install -y php php-mysql mysql-server
```

### Como instalar no Windows (Exemplo com XAMPP/WAMP)

Se você usa Windows, a maneira mais fácil é instalar um pacote como XAMPP ou WAMP Server, que já vêm com Apache, MySQL e PHP configurados.

## 📦 Configuração do Projeto

1.  **Baixe o Projeto:**
    Se você tem acesso ao repositório, clone-o:
    ```bash
git clone <URL_DO_REPOSITORIO>
cd pet_adoption_website
    ```
    Caso contrário, baixe o arquivo ZIP do projeto e extraia-o para uma pasta de sua preferência (ex: `C:\xampp\htdocs\pet_adoption_website` ou `/var/www/html/pet_adoption_website`).

2.  **Estrutura de Pastas:**
    Certifique-se de que a estrutura de pastas seja similar a esta:
    ```
    pet_adoption_website/
    ├── public/                 # Arquivos públicos (HTML, CSS, JS, PHP)
    │   ├── index.html
    │   ├── cadastro.html
    │   ├── agendamento.html
    │   ├── admin_consultas.php
    │   ├── style.css
    │   ├── script.js
    │   ├── process_animal.php
    │   ├── process_agendamento.php
    │   ├── get_animals.php
    │   ├── get_animal.php
    │   ├── images/            # Imagens do site
    │   └── uploads/           # Fotos dos animais (crie esta pasta se não existir)
    ├── includes/              # Arquivos de configuração
    │   └── db_config.php
    └── README.md
    ```
    **Importante:** Crie a pasta `public/uploads` se ela não existir. Ela será usada para armazenar as fotos dos animais.

## 🗄️ Configuração do Banco de Dados MySQL

1.  **Inicie o Serviço MySQL:**
    No Linux, você pode iniciar o serviço com:
    ```bash
sudo systemctl start mysql
sudo systemctl enable mysql # Para iniciar automaticamente no boot
    ```
    No Windows (XAMPP/WAMP), inicie o serviço MySQL através do painel de controle do XAMPP/WAMP.

2.  **Acesse o MySQL:**
    Abra um terminal ou prompt de comando e acesse o MySQL como root (ou um usuário com permissões para criar bancos de dados e usuários):
    ```bash
sudo mysql -u root -p
    ```
    (Será solicitada a senha do root do MySQL, se houver).

3.  **Crie o Banco de Dados e Usuário:**
    Dentro do prompt do MySQL, execute os seguintes comandos para criar o banco de dados `petamigo_db` e um usuário `petamigo` com a senha `petamigo123` (você pode alterar a senha):
    ```sql
CREATE DATABASE petamigo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'petamigo'@'localhost' IDENTIFIED BY 'petamigo123';
GRANT ALL PRIVILEGES ON petamigo_db.* TO 'petamigo'@'localhost';
FLUSH PRIVILEGES;
EXIT;
    ```

4.  **Crie as Tabelas:**
    Ainda no terminal, ou usando uma ferramenta como phpMyAdmin, execute as seguintes queries para criar as tabelas `animais` e `consultas` no banco de dados `petamigo_db`:

    ```sql
USE petamigo_db;

CREATE TABLE animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especie ENUM('cachorro', 'gato') NOT NULL,
    idade VARCHAR(50),
    raca VARCHAR(100),
    pelagem VARCHAR(100),
    temperamento VARCHAR(200),
    descricao TEXT,
    foto VARCHAR(255),
    status ENUM('disponivel', 'adotado') DEFAULT 'disponivel',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_adotante VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    observacoes TEXT,
    status ENUM('agendado', 'realizado', 'cancelado') DEFAULT 'agendado',
    data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    ```

5.  **Configure `db_config.php`:**
    Abra o arquivo `includes/db_config.php` e verifique se as credenciais do banco de dados correspondem às que você criou:
    ```php
    <?php

    class Database {
        private $host = "localhost";
        private $db_name = "petamigo_db";
        private $username = "petamigo";
        private $password = "petamigo123";
        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            } catch(PDOException $exception) {
                echo "Erro de conexão: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }

    ?>
    ```
    Se você alterou o nome do banco de dados, usuário ou senha, atualize este arquivo.

## ▶️ Como Iniciar o Servidor

### Usando o PHP Built-in Server (Recomendado para Desenvolvimento)

1.  Abra um terminal ou prompt de comando.
2.  Navegue até a pasta `pet_adoption_website` (o diretório raiz do projeto, não a pasta `public`).
    ```bash
cd /caminho/para/seu/pet_adoption_website
    ```
3.  Inicie o servidor PHP apontando para a pasta `public`:
    ```bash
php -S localhost:8080 -t public/
    ```
    Isso iniciará um servidor web na porta `8080` e servirá os arquivos da pasta `public`.

4.  Abra seu navegador e acesse:
    [http://localhost:8080](http://localhost:8080)

### Usando Apache/Nginx (Se você configurou um servidor web completo)

1.  Certifique-se de que seu servidor web (Apache ou Nginx) esteja configurado para servir a pasta `public` do projeto.
2.  Inicie o serviço do Apache/Nginx.
3.  Acesse o site através do endereço configurado para o seu servidor (ex: `http://localhost/pet_adoption_website/` ou `http://petamigo.local`).

## ✅ Testando as Funcionalidades

Após iniciar o servidor, você pode testar as seguintes funcionalidades:

-   **Página Inicial:** Verifique se os cards de animais são carregados (inicialmente vazios, pois o banco está vazio).
-   **Cadastrar Animal:** Acesse `/cadastro.html`, preencha o formulário e cadastre um animal. Verifique se a foto é enviada para `public/uploads` e se o animal aparece na página inicial.
-   **Agendar Consulta:** Acesse `/agendamento.html`, preencha o formulário e agende uma consulta.
-   **Painel Administrativo:** Acesse `/admin_consultas.php` para ver as consultas agendadas.

Se tiver qualquer problema, verifique os logs do seu servidor PHP e MySQL para mensagens de erro. Boa sorte!

