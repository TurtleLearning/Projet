document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    let isSubmitting = false;

    // Fonction de validation du nom
    function validateNom(nom) {
        return /^[A-Za-zÀ-ÿ\s-]{2,50}$/.test(nom);
    }

    // Fonction de validation du contact (email ou téléphone)
    function validateContact(contact) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        return emailRegex.test(contact) || phoneRegex.test(contact);
    }

    // Fonction de validation du message
    function validateMessage(message) {
        return message.length >= 10 && message.length <= 1000;
    }

    // Fonction pour afficher les erreurs
    function showError(input, message) {
        const errorDiv = input.nextElementSibling?.classList.contains('error-message') 
            ? input.nextElementSibling 
            : document.createElement('div');
        
        if (!input.nextElementSibling?.classList.contains('error-message')) {
            errorDiv.className = 'error-message text-danger mt-1';
            input.parentNode.insertBefore(errorDiv, input.nextElementSibling);
        }
        
        errorDiv.textContent = message;
        input.classList.add('is-invalid');
    }

    // Fonction pour effacer les erreurs
    function clearError(input) {
        const errorDiv = input.nextElementSibling;
        if (errorDiv?.classList.contains('error-message')) {
            errorDiv.remove();
        }
        input.classList.remove('is-invalid');
    }

    // Validation en temps réel
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', function() {
            clearError(this);
            
            switch(this.id) {
                case 'nom':
                    if (!validateNom(this.value)) {
                        showError(this, 'Le nom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets');
                    }
                    break;
                case 'contact':
                    if (!validateContact(this.value)) {
                        showError(this, 'Veuillez entrer un email ou un numéro de téléphone valide');
                    }
                    break;
                case 'message':
                    if (!validateMessage(this.value)) {
                        showError(this, 'Le message doit contenir entre 10 et 1000 caractères');
                    }
                    break;
            }
        });
    });

    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            return;
        }

        const nom = form.querySelector('#nom').value;
        const contact = form.querySelector('#contact').value;
        const message = form.querySelector('#message').value;
        let hasError = false;

        // Validation du nom
        if (!validateNom(nom)) {
            showError(form.querySelector('#nom'), 'Le nom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets');
            hasError = true;
        }

        // Validation du contact
        if (!validateContact(contact)) {
            showError(form.querySelector('#contact'), 'Veuillez entrer un email ou un numéro de téléphone valide');
            hasError = true;
        }

        // Validation du message
        if (!validateMessage(message)) {
            showError(form.querySelector('#message'), 'Le message doit contenir entre 10 et 1000 caractères');
            hasError = true;
        }

        if (!hasError) {
            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Envoi en cours...';
            form.submit();

            // Ajout d'un message de confirmation
            const confirmationMessage = document.createElement('div');
            confirmationMessage.className = 'confirmation-message text-success mt-2';
            confirmationMessage.textContent = 'Votre message a bien été envoyé !';
            form.appendChild(confirmationMessage);
        }
    });
});