function openModal(type) {
    // Contenu des modales
    const contents = {
        'cgv': {
            title: 'Conditions Générales de Vente',
            content: 'Votre contenu CGV ici...'
        },
        'privacy': {
            title: 'Politique de Confidentialité',
            content: 'Votre contenu politique de confidentialité ici...'
        },
        'cookies': {
            title: 'Gestion des Cookies',
            content: 'Votre contenu cookies ici...'
        }
    };

    // Création de la carte modale
    const modal = document.createElement('div');
    modal.className = 'modal-card';
    modal.innerHTML = `
        <div class="modal-header">
            <h2>${contents[type].title}</h2>
            <button onclick="closeModal(this)" class="close-button">&times;</button>
        </div>
        <div class="modal-content">
            ${contents[type].content}
        </div>
    `;

    document.body.appendChild(modal);
    
    // Animation d'entrée
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function closeModal(button) {
    const modal = button.closest('.modal-card');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.remove();
    }, 300); // Correspond à la durée de la transition
} 