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
 
    // Fonction de validation générique
    function validateInput(input) {
        let hasError = false;
 
        switch(input.id) {
            case 'nom':
                if (!validateNom(input.value)) {
                    showError(input, 'Le nom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets');
                    hasError = true;
                }
                break;
            case 'contact':
                if (!validateContact(input.value)) {
                    showError(input, 'Veuillez entrer un email ou un numéro de téléphone valide');
                    hasError = true;
                }
                break;
            case 'message':
                if (!validateMessage(input.value)) {
                    showError(input, 'Le message doit contenir entre 10 et 1000 caractères');
                    hasError = true;
                }
                break;
        }
        return hasError;
    }
 
    // Validation en temps réel
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', function() {
            clearError(this);
            validateInput(this);
        });
    });
 
    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let hasFormError = false;
 
        // Validation de tous les champs
        form.querySelectorAll('input, textarea').forEach(input => {
            if (validateInput(input)) {
                hasFormError = true;
            }
        });
 
        // Si pas d'erreur et pas déjà en cours d'envoi
        if (!hasFormError && !isSubmitting) {
            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Envoi en cours...';
            
            // Envoi du formulaire
            form.submit();
 
            // Message de confirmation
            const confirmationMessage = document.createElement('div');
            confirmationMessage.className = 'confirmation-message text-success mt-2';
            confirmationMessage.textContent = 'Votre message a bien été envoyé !';
            const header = document.querySelector('header');
            header.insertAdjacentElement('afterend', confirmationMessage);
 
            // Suppression du message après 3 secondes
            setTimeout(() => {
                confirmationMessage.remove();
            }, 3000);
        }
    });
 });