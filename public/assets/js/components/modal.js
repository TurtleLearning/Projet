class Modal {
    // Configuration personnalisable via les options
    constructor(options = {}) {
        this.modalId = options.modalId || 'modalContainer';
        this.overlayId = options.overlayId || 'modalOverlay';
        this.closeButtonClass = options.closeButtonClass || 'close-button';
        this.activeClass = options.activeClass || 'active';
        
        this.init();
    }
    
    // Initialisation des éléments DOM
    init() {
        this.modal = document.getElementById(this.modalId);
        this.overlay = document.getElementById(this.overlayId);
        this.closeButton = this.modal.querySelector(`.${this.closeButtonClass}`);
        
        this.bindEvents();
    }
    
    // Gestion des événements centralisée
    bindEvents() {
        this.closeButton.addEventListener('click', () => this.close());
        this.overlay.addEventListener('click', () => this.close());
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.close();
        });
    }
    
    // Méthode d'ouverture améliorée avec gestion du contenu
    open(content, title = '') {
        this.modal.querySelector('#modalTitle').textContent = title;
        this.modal.querySelector('#modalContent').innerHTML = content;
        
        this.modal.classList.add(this.activeClass);
        this.overlay.classList.add(this.activeClass);
        document.body.style.overflow = 'hidden';
    }
    
    // Méthode de fermeture
    close() {
        this.modal.classList.remove(this.activeClass);
        this.overlay.classList.remove(this.activeClass);
        document.body.style.overflow = '';
    }
} 