if (document.body.getAttribute('data-page') === 'reservation') {
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        let isSubmitting = false;

        // Soumission avec validation
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
}