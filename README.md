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

