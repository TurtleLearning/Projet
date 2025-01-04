document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    let isSubmitting = false;

    // Fonction de validation du nom
    function validateNom(nom) {
        return /^[A-Za-zÀ-ÿ\s-]{2,50}$/.test(nom);
    }

    // Fonction de validation du contact (email ou téléphone)
    function validatePrenom(prenom) {
        return /^[A-Za-zÀ-ÿ\s-]{2,50}$/.test(prenom);
    }

    // Fonction de validation du message
    function validateTelephone(num_tel) {
        const phoneRegex = /^\d{10}$/;
        return phoneRegex.test(num_tel);
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateTotalPersonnes (nombre_total) {
        return total > 0;
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
                case 'prenom':
                    if (!validatePrenom(this.value)) {
                        showError(this, 'Le prenom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets');
                    }
                    break;
                case 'num_tel':
                    if (!validateTelephone(this.value)) {
                        showError(this, 'Le numéro doit faire 10 chiffres');
                    }
                    break;
                case 'email':
                    if (!validateEmail(this.value)) {
                        showError(this, `L'email n'est pas valide, veillez à respecter ce format : xx@xx.xx`);
                    }
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
        const prenom = form.querySelector('#prenom').value;
        const num_tel = form.querySelector('#num_tel').value;
        const email = form.querySelector('#email').value;

        let hasError = false;

        // Validation du nom
        if (!validateNom(nom)) {
            showError(form.querySelector('#nom'), 'Le nom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets.');
            hasError = true;
        }

        // Validation du prenom
        if (!validateNom(nom)) {
            showError(form.querySelector('#prenom'), 'Le prenom doit contenir entre 2 et 50 caractères et ne peut contenir que des lettres, espaces et tirets.');
            hasError = true;
        }

        // Validation du numéro de téléphone
        if (!validateMessage(num_tel)) {
            showError(form.querySelector('#num_tel'), 'Le numéro de téléphone ne doit contenir que 10 chiffres et seulement 10.');
            hasError = true;
        }

        // Validation de l'email
        if (!validateEmail(email)) {
            showError(form.querySelector('#email'), `L'email n'est pas valide, veillez à respecter ce format : xx@xx.xx`);
            hasError = true;
        }

        if (!hasError) {
            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Envoi en cours...';
            form.submit();
        }
    });
});