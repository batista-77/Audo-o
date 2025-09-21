# Implantação Permanente do PetAmigo com XAMPP

Como você já tem o XAMPP instalado, a 


implantação permanente do site PetAmigo será uma extensão do guia de configuração local, focando em garantir que o site esteja sempre acessível e configurado corretamente no seu ambiente XAMPP.

## 🚀 Visão Geral da Implantação com XAMPP

O XAMPP já fornece um servidor Apache e um servidor MySQL. A ideia é colocar os arquivos do projeto no diretório `htdocs` do XAMPP e garantir que os serviços estejam sempre ativos.

## 📋 Passo a Passo para Implantação Permanente

### 1. Preparar os Arquivos do Projeto

1.  **Localize o Diretório `htdocs` do XAMPP:**
    Geralmente, o diretório `htdocs` está localizado em `C:\xampp\htdocs` (no Windows) ou `/Applications/XAMPP/htdocs` (no macOS).

2.  **Copie o Projeto para `htdocs`:**
    Mova a pasta `pet_adoption_website` (que contém `public/`, `includes/`, `README.md`, etc.) para dentro do diretório `htdocs` do XAMPP.
    Após mover, o caminho do seu projeto será algo como `C:\xampp\htdocs\pet_adoption_website`.

3.  **Crie a Pasta `uploads` (se não existir):**
    Dentro da pasta `C:\xampp\htdocs\pet_adoption_website\public\`, crie uma pasta chamada `uploads`. Esta pasta será usada para armazenar as fotos dos animais que forem cadastradas.

### 2. Configurar o Banco de Dados MySQL (se ainda não fez)

Se você já seguiu o guia de configuração local e criou o banco de dados e as tabelas, pode pular esta etapa. Caso contrário, siga as instruções abaixo:

1.  **Inicie os Serviços MySQL e Apache do XAMPP:**
    Abra o "XAMPP Control Panel" e inicie os módulos "Apache" e "MySQL". Certifique-se de que ambos estejam rodando (geralmente indicados por uma cor verde).

2.  **Acesse o phpMyAdmin:**
    Abra seu navegador e digite `http://localhost/phpmyadmin`. Isso abrirá a interface do phpMyAdmin, onde você pode gerenciar seus bancos de dados.

3.  **Crie o Banco de Dados e Usuário:**
    *   No phpMyAdmin, clique em "Bancos de dados" na barra superior.
    *   No campo "Criar banco de dados", digite `petamigo_db` e clique em "Criar".
    *   Para criar o usuário e conceder permissões, você pode ir na aba "SQL" e executar os comandos:
        ```sql
CREATE USER 'petamigo'@'localhost' IDENTIFIED BY 'petamigo123';
GRANT ALL PRIVILEGES ON petamigo_db.* TO 'petamigo'@'localhost';
FLUSH PRIVILEGES;
        ```
        (Você pode alterar a senha `petamigo123` se desejar. Se você não criar um usuário específico, o XAMPP geralmente usa `root` sem senha para `localhost`, mas criar um usuário dedicado é uma boa prática).

4.  **Crie as Tabelas:**
    *   No phpMyAdmin, selecione o banco de dados `petamigo_db` que você acabou de criar.
    *   Vá para a aba "SQL" e execute as seguintes queries para criar as tabelas `animais` e `consultas`:

    ```sql
CREATE TABLE animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especie ENUM(\'cachorro\', \'gato\') NOT NULL,
    idade VARCHAR(50),
    raca VARCHAR(100),
    pelagem VARCHAR(100),
    temperamento VARCHAR(200),
    descricao TEXT,
    foto VARCHAR(255),
    status ENUM(\'disponivel\', \'adotado\') DEFAULT \'disponivel\',
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
    status ENUM(\'agendado\', \'realizado\', \'cancelado\') DEFAULT \'agendado\',
    data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    ```

5.  **Verifique `db_config.php`:**
    Abra o arquivo `pet_adoption_website/includes/db_config.php` e certifique-se de que as credenciais (`$username` e `$password`) correspondem ao usuário que você configurou no MySQL (se usou `root` sem senha, ajuste `petamigo` para `root` e `petamigo123` para uma string vazia `""`).

### 3. Acessar o Site

1.  Com os serviços Apache e MySQL do XAMPP rodando, abra seu navegador.
2.  Acesse o site digitando:
    `http://localhost/pet_adoption_website/public/`

    Você também pode configurar um Virtual Host no Apache do XAMPP para ter um URL mais amigável, como `http://petamigo.local`, mas isso é um passo avançado e opcional.

### 4. Garantir Acessibilidade Permanente

Para que o site esteja "permanentemente" disponível na sua máquina, você precisa garantir que os serviços Apache e MySQL do XAMPP estejam sempre rodando.

*   **XAMPP Control Panel:** No Windows, você pode configurar o XAMPP para iniciar os módulos Apache e MySQL automaticamente quando o XAMPP Control Panel é aberto. Além disso, você pode instalar os serviços como serviços do Windows para que eles iniciem com o sistema operacional.
    *   No XAMPP Control Panel, clique nos botões "Service" ao lado de Apache e MySQL para instalá-los como serviços. Isso fará com que eles iniciem automaticamente com o Windows.

## ✅ Testando a Implantação

Após seguir todos os passos, teste as funcionalidades do site:

-   Acesse a página inicial e veja se os animais são carregados (após cadastrar alguns).
-   Cadastre um novo animal e verifique se ele aparece na lista e se a foto é salva na pasta `public/uploads`.
-   Agende uma consulta e verifique se ela aparece na página `admin_consultas.php`.

Se tiver qualquer problema, verifique os logs do Apache e MySQL no XAMPP Control Panel para identificar erros.

Com isso, seu site PetAmigo estará rodando permanentemente na sua máquina local via XAMPP!

