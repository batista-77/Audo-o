/**
 * JavaScript Principal - PetAmigo
 * Sistema de Adoção de Animais
 */

// Aguardar carregamento do DOM
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

/**
 * Inicializar aplicação
 */
function initializeApp() {
    setupMobileMenu();
    setupModal();
    setupFormValidation();
    loadAnimals();
    setupSmoothScrolling();
    showMessages();
}

/**
 * Configurar menu mobile (hambúrguer)
 */
function setupMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenu && navMenu) {
        mobileMenu.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Fechar menu ao clicar em um link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
}

/**
 * Configurar modal
 */
function setupModal() {
    const modal = document.getElementById('animal-modal');
    const closeBtn = document.querySelector('.close');
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

/**
 * Abrir modal com informações do animal
 */
function openModal(animalId) {
    const modal = document.getElementById('animal-modal');
    const modalBody = document.getElementById('modal-body');
    
    if (!modal || !modalBody) return;
    
    // Mostrar loading
    modalBody.innerHTML = `
        <div style="text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
            <p style="margin-top: 1rem;">Carregando...</p>
        </div>
    `;
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Buscar dados do animal
    fetch(`get_animal.php?id=${animalId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAnimalModal(data.data);
            } else {
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #e74c3c;"></i>
                        <p style="margin-top: 1rem;">Erro ao carregar informações do animal.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #e74c3c;"></i>
                    <p style="margin-top: 1rem;">Erro de conexão.</p>
                </div>
            `;
        });
}

/**
 * Exibir informações do animal no modal
 */
function displayAnimalModal(animal) {
    const modalBody = document.getElementById('modal-body');
    
    modalBody.innerHTML = `
        <div class="modal-animal-info">
            <div class="modal-animal-image">
                <img src="${animal.foto_url}" alt="${animal.nome}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 10px;">
            </div>
            <div class="modal-animal-details" style="margin-top: 1.5rem;">
                <h2 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 2rem;">${animal.nome}</h2>
                
                <div class="animal-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="info-item">
                        <strong style="color: var(--text-dark);">Espécie:</strong>
                        <span style="color: var(--text-light);">${animal.especie.charAt(0).toUpperCase() + animal.especie.slice(1)}</span>
                    </div>
                    ${animal.idade ? `
                        <div class="info-item">
                            <strong style="color: var(--text-dark);">Idade:</strong>
                            <span style="color: var(--text-light);">${animal.idade}</span>
                        </div>
                    ` : ''}
                    ${animal.raca ? `
                        <div class="info-item">
                            <strong style="color: var(--text-dark);">Raça:</strong>
                            <span style="color: var(--text-light);">${animal.raca}</span>
                        </div>
                    ` : ''}
                    ${animal.pelagem ? `
                        <div class="info-item">
                            <strong style="color: var(--text-dark);">Pelagem:</strong>
                            <span style="color: var(--text-light);">${animal.pelagem}</span>
                        </div>
                    ` : ''}
                </div>
                
                ${animal.temperamento ? `
                    <div class="temperament-section" style="margin-bottom: 1.5rem;">
                        <strong style="color: var(--text-dark);">Temperamento:</strong>
                        <p style="color: var(--accent-color); font-style: italic; margin-top: 0.5rem;">${animal.temperamento}</p>
                    </div>
                ` : ''}
                
                ${animal.descricao ? `
                    <div class="description-section" style="margin-bottom: 1.5rem;">
                        <strong style="color: var(--text-dark);">Sobre ${animal.nome}:</strong>
                        <p style="color: var(--text-light); line-height: 1.6; margin-top: 0.5rem;">${animal.descricao}</p>
                    </div>
                ` : ''}
                
                <div class="modal-actions" style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button onclick="window.location.href='agendamento.html'" class="btn-primary" style="flex: 1; padding: 1rem; border: none; border-radius: 25px; background-color: var(--primary-color); color: var(--white); font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-calendar-plus"></i> Agendar Visita
                    </button>
                    <button onclick="closeModal()" class="btn-secondary" style="flex: 1; padding: 1rem; border: 2px solid var(--primary-color); border-radius: 25px; background-color: transparent; color: var(--primary-color); font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        Fechar
                    </button>
                </div>
                
                <div class="cadastro-info" style="text-align: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <small style="color: var(--text-light);">Cadastrado em ${animal.data_cadastro_formatada}</small>
                </div>
            </div>
        </div>
    `;
}

/**
 * Fechar modal
 */
function closeModal() {
    const modal = document.getElementById('animal-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

/**
 * Carregar animais dinamicamente
 */
function loadAnimals() {
    const container = document.getElementById('animals-container');
    if (!container) return;
    
    fetch('get_animals.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                displayAnimals(data.data);
            }
        })
        .catch(error => {
            console.error('Erro ao carregar animais:', error);
        });
}

/**
 * Exibir animais na página
 */
function displayAnimals(animals) {
    const container = document.getElementById('animals-container');
    if (!container) return;
    
    // Limpar container (remover exemplos estáticos)
    container.innerHTML = '';
    
    animals.forEach(animal => {
        const animalCard = createAnimalCard(animal);
        container.appendChild(animalCard);
    });
}

/**
 * Criar card de animal
 */
function createAnimalCard(animal) {
    const card = document.createElement('div');
    card.className = 'animal-card';
    
    card.innerHTML = `
        <div class="card-image">
            <img src="${animal.foto_url}" alt="${animal.nome}" onerror="this.src='images/default-${animal.especie}.jpg'">
        </div>
        <div class="card-content">
            <h3>${animal.nome}</h3>
            <p class="animal-info">
                ${animal.idade ? `<span class="age">${animal.idade}</span>` : ''}
                ${animal.idade && animal.raca ? ' • ' : ''}
                ${animal.raca ? `<span class="breed">${animal.raca}</span>` : ''}
            </p>
            ${animal.temperamento ? `<p class="temperament">${animal.temperamento}</p>` : ''}
            <div class="card-actions">
                <button class="btn-primary" onclick="openModal(${animal.id})">Ver Mais</button>
                <button class="btn-secondary" onclick="window.location.href='agendamento.html'">Adotar</button>
            </div>
        </div>
    `;
    
    return card;
}

/**
 * Configurar validação de formulários
 */
function setupFormValidation() {
    // Formulário de cadastro de animal
    const animalForm = document.getElementById('animal-form');
    if (animalForm) {
        animalForm.addEventListener('submit', function(e) {
            if (!validateAnimalForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Formulário de agendamento
    const agendamentoForm = document.getElementById('agendamento-form');
    if (agendamentoForm) {
        agendamentoForm.addEventListener('submit', function(e) {
            if (!validateAgendamentoForm()) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Validar formulário de cadastro de animal
 */
function validateAnimalForm() {
    const nome = document.getElementById('nome');
    const especie = document.getElementById('especie');
    
    let isValid = true;
    
    // Limpar mensagens de erro anteriores
    clearErrorMessages();
    
    // Validar nome
    if (!nome.value.trim()) {
        showFieldError(nome, 'Nome do animal é obrigatório');
        isValid = false;
    }
    
    // Validar espécie
    if (!especie.value) {
        showFieldError(especie, 'Espécie é obrigatória');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validar formulário de agendamento
 */
function validateAgendamentoForm() {
    const nome = document.getElementById('nome_adotante');
    const telefone = document.getElementById('telefone');
    const email = document.getElementById('email');
    const data = document.getElementById('data');
    const horario = document.getElementById('horario');
    
    let isValid = true;
    
    // Limpar mensagens de erro anteriores
    clearErrorMessages();
    
    // Validar nome
    if (!nome.value.trim()) {
        showFieldError(nome, 'Nome completo é obrigatório');
        isValid = false;
    }
    
    // Validar telefone
    if (!telefone.value.trim()) {
        showFieldError(telefone, 'Telefone é obrigatório');
        isValid = false;
    }
    
    // Validar email
    if (!email.value.trim()) {
        showFieldError(email, 'E-mail é obrigatório');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showFieldError(email, 'E-mail inválido');
        isValid = false;
    }
    
    // Validar data
    if (!data.value) {
        showFieldError(data, 'Data da visita é obrigatória');
        isValid = false;
    } else {
        const selectedDate = new Date(data.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            showFieldError(data, 'A data não pode ser no passado');
            isValid = false;
        } else if (selectedDate.getDay() === 0) {
            showFieldError(data, 'Não atendemos aos domingos');
            isValid = false;
        }
    }
    
    // Validar horário
    if (!horario.value) {
        showFieldError(horario, 'Horário é obrigatório');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Mostrar erro em campo específico
 */
function showFieldError(field, message) {
    field.style.borderColor = '#e74c3c';
    
    // Remover mensagem de erro anterior
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Adicionar nova mensagem de erro
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.fontSize = '0.8rem';
    errorDiv.style.marginTop = '0.3rem';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Limpar mensagens de erro
 */
function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    const fields = document.querySelectorAll('input, select, textarea');
    fields.forEach(field => {
        field.style.borderColor = '';
    });
}

/**
 * Validar formato de email
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Configurar scroll suave
 */
function setupSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 70; // Compensar navbar fixa
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Mostrar mensagens de sucesso/erro da URL
 */
function showMessages() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    const message = urlParams.get('message');
    
    if (success && message) {
        showNotification(decodeURIComponent(message), 'success');
        // Limpar URL
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (error && message) {
        showNotification(decodeURIComponent(message), 'error');
        // Limpar URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

/**
 * Mostrar notificação
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 90px;
        right: 20px;
        background-color: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Adicionar CSS para animação
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

