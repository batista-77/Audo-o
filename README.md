# PetAmigo - Sistema de Adoção de Cães e Gatos

Um site web completo e responsivo para cadastro e adoção de cães e gatos de rua, desenvolvido com PHP, HTML, CSS, JavaScript e MySQL.

## 🌐 Site Implantado

**URL de Acesso:** https://8080-i05tgqvxnoikkhubazo99-e36a2d7b.manusvm.computer

## 📋 Funcionalidades

### Para Visitantes
- Visualizar animais disponíveis para adoção
- Ver detalhes completos de cada animal em modal interativo
- Agendar consultas para conhecer os animais
- Interface responsiva para todos os dispositivos

### Para Administradores
- Cadastrar novos animais no sistema
- Gerenciar consultas agendadas
- Upload de fotos dos animais
- Painel administrativo para visualizar agendamentos

## 🎨 Design

### Paleta de Cores
- **Primária:** #8B7355 (Marrom suave)
- **Secundária:** #F5F1EB (Bege claro)
- **Accent:** #D4A574 (Dourado suave)
- **Texto:** #5D4E37 (Marrom escuro)
- **Background:** #FAF8F5 (Bege muito claro)

### Características Visuais
- Design responsivo com Flexbox e CSS Grid
- Menu hambúrguer para dispositivos móveis
- Cards interativos para exibição dos animais
- Modal para detalhes completos dos pets
- Formulários com validação visual
- Animações suaves e transições

## 🛠️ Tecnologias Utilizadas

### Frontend
- **HTML5** - Estrutura semântica
- **CSS3** - Estilização responsiva
- **JavaScript** - Interatividade e validações
- **Font Awesome** - Ícones

### Backend
- **PHP 8.1** - Lógica do servidor
- **MySQL** - Banco de dados
- **APIs REST** - Comunicação frontend/backend

## 📁 Estrutura do Projeto

```
pet_adoption_website/
├── public/                 # Arquivos públicos
│   ├── index.html         # Página inicial
│   ├── cadastro.html      # Cadastro de animais
│   ├── agendamento.html   # Agendamento de consultas
│   ├── admin_consultas.php # Painel administrativo
│   ├── style.css          # Estilos principais
│   ├── script.js          # JavaScript principal
│   ├── process_animal.php # Processamento de cadastro
│   ├── process_agendamento.php # Processamento de agendamento
│   ├── get_animals.php    # API para listar animais
│   ├── get_animal.php     # API para animal específico
│   ├── images/            # Imagens do site
│   └── uploads/           # Upload de fotos
├── includes/              # Arquivos de configuração
│   └── db_config.php     # Configuração do banco
└── README.md             # Documentação
```

<!-- ## 🗄️ Banco de Dados

### Tabela `animais`
```sql
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
```

### Tabela `consultas`
```sql
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

## 🚀 Como Usar

### Navegação Principal
1. **Início** - Página principal com banner e animais em destaque
2. **Animais Disponíveis** - Lista completa de pets para adoção
3. **Agendar Consulta** - Formulário para marcar visitas
4. **Cadastrar Animal** - Formulário para adicionar novos pets

### Funcionalidades Interativas
- **Cards de Animais**: Clique em "Ver Mais" para detalhes completos
- **Modal Informativo**: Visualize todas as informações do pet
- **Formulários Validados**: Campos obrigatórios e validação em tempo real
- **Responsividade**: Funciona perfeitamente em mobile, tablet e desktop

## 📱 Responsividade

O site foi desenvolvido com abordagem "mobile-first" e inclui:
- Menu hambúrguer para dispositivos móveis
- Layout flexível que se adapta a qualquer tela
- Imagens otimizadas para diferentes resoluções
- Formulários adaptáveis para touch screens

## 🔧 Configuração Local

### Pré-requisitos
- PHP 8.1 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server

### Instalação
1. Clone ou baixe o projeto
2. Configure o banco de dados MySQL
3. Ajuste as credenciais em `includes/db_config.php`
4. Execute as queries de criação das tabelas
5. Inicie o servidor PHP: `php -S localhost:8080`

## 🎯 Recursos Implementados

### ✅ Funcionalidades Completas
- Sistema de cadastro de animais com upload de fotos
- Exibição dinâmica de pets disponíveis
- Modal interativo com detalhes completos
- Agendamento de consultas com validação
- Painel administrativo para gestão
- Design responsivo e acessível
- Validação de formulários client-side e server-side
- APIs REST para comunicação assíncrona

### 🔒 Segurança
- Validação de dados no frontend e backend
- Sanitização de inputs para prevenir XSS
- Prepared statements para prevenir SQL injection
- Upload seguro de arquivos com validação de tipo

## 📞 Informações de Contato

### Horários de Funcionamento
- **Segunda a Sexta:** 8h às 17h
- **Sábados:** 8h às 12h
- **Domingos:** Fechado

### O que Levar na Visita
- Documento de identidade
- Comprovante de residência

## 🤝 Contribuição

Este projeto foi desenvolvido como um sistema completo para ONGs e organizações que trabalham com adoção de animais. Todas as funcionalidades foram implementadas seguindo as melhores práticas de desenvolvimento web.

## 📄 Licença

Projeto desenvolvido para fins educacionais e de demonstração. Livre para uso e modificação.

---

**PetAmigo** - Conectando corações e transformando vidas através da adoção responsável. 🐕🐱❤️
 -->
