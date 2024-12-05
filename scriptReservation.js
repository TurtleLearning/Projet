document.addEventListener('DOMContentLoaded', function() {
    const quantiteNuit = document.getElementById('quantite-nuit');
    const quantiteRepasmidi = document.getElementById('quantite-Repas-midi');
    const quantiteRepassoir = document.getElementById('quantite-Repas-soir');
    const verifierBtn = document.getElementById('verifier-btn');
    const imprimerBtn = document.getElementById('imprimer-btn');
    const envoyerBtn = document.getElementById('envoyer-btn');
    const reinitialiserBtn = document.getElementById('reinitialiser-btn');

    quantiteNuit.addEventListener('input', calculateTotals);
    quantiteRepasmidi.addEventListener('input', calculateTotals);
    quantiteRepassoir.addEventListener('input', calculateTotals);
    
    verifierBtn.addEventListener('click', verifierAvantEnvoi);
    imprimerBtn.addEventListener('click', () => window.print());
    if (reinitialiserBtn) {
        reinitialiserBtn.addEventListener('click', resetForm);
    } else {
        console.error("Bouton de réinitialisation non trouvé.");
        showError("Une erreur est survenue. Veuillez réessayer plus tard.");
    }

    envoyerBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton
        if (validateForm()) {
            const formData = new FormData(document.forms[0]); // Récupère les données du formulaire

            console.log("Données envoyées :", {
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                num_tel: document.getElementById('num_tel').value,
                email: document.getElementById('email').value,
                quantiteNuit: document.getElementById('quantite-nuit').value,
                quantiteRepasMidi: document.getElementById('quantite-Repas-midi').value,
                quantiteRepasSoir: document.getElementById('quantite-Repas-soir').value
            });

            fetch('reservation.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Affiche le message de retour de reservation.php
                window.location.href = 'index.html'; // Redirige vers la page d'accueil
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'envoi des données.');
            });
        }
    });
    
});

function calculateTotals() {
    const Nuit = parseInt(document.getElementById('quantite-nuit').value) || 0;
    const repasMidi = parseInt(document.getElementById('quantite-Repas-midi').value) || 0;
    const repasSoir = parseInt(document.getElementById('quantite-Repas-soir').value) || 0;
    
    let PRIX_NUIT = 15;
    let PRIX_REPAS_MIDI = 7;
    let PRIX_REPAS_SOIR = 15;
    
    const nuitTotal = Nuit * PRIX_NUIT;
    const repasmidiTotal = repasMidi * PRIX_REPAS_MIDI;
    const repassoirTotal = repasSoir * PRIX_REPAS_SOIR;
    
    document.getElementById('sous-total-nuit').value = nuitTotal.toFixed(2) + ' euros';
    document.getElementById('sous-total-repas-midi').value = repasmidiTotal.toFixed(2) + ' euros';
    document.getElementById('sous-total-repas-soir').value = repassoirTotal.toFixed(2) + ' euros';
    
    const sousTotal = nuitTotal + repasmidiTotal + repassoirTotal;
    const totalTTC = sousTotal * 1.2;
    
    document.getElementById('sous-total').value = sousTotal.toFixed(2) + ' euros';
    document.getElementById('total-ttc').value = totalTTC.toFixed(2) + ' euros';
}

function verifierAvantEnvoi() {
    if (validateForm()) {
        const nom = document.getElementById('nom');
        nom.value = nom.value.toUpperCase();
        alert('Formulaire vérifié avec succès.');
    }
}

function envoyerCommande() {
    if (validateForm()) {
        const nom = document.getElementById('nom');
        const num_tel = document.getElementById('num_tel');
        const email = document.getElementById('email');
        
        alert('Votre demande de réservation a bien été effectuée, vous recevrez le récapitulatif de votre demande par mail sous peu. Merci de votre confiance.');
    }
}

function validateForm() {
    const nom = document.getElementById('nom');
    const email = document.getElementById('email');
    const num_tel = document.getElementById('num_tel');
    const conditions = document.getElementById('conditions');

    let valid = true;

    if (nom.value.trim() === '') {
        nom.classList.add('error');
        alert('Le champ Nom est obligatoire.');
        valid = false;
    } else {
        nom.classList.remove('error');
    }

    if (num_tel.value.trim() === '' || /[a-zA-Z]/.test(num_tel.value) || num_tel.value.length !== 10) {
        num_tel.classList.add('error');
        alert('Veuillez entrer un numéro de téléphone valide, contenant uniquement 10 chiffres.');
        valid = false;
    } else {
        num_tel.classList.remove('error');
    }

    if (email.value.trim() === '' || !email.value.includes('@')) {
        email.classList.add('error');
        alert('Veuillez entrer un email valide.');
        valid = false;
    } else {
        email.classList.remove('error');
    }

    if (!conditions.checked) {
        alert('Vous devez accepter les conditions générales.');
        valid = false;
    }

    return valid;
}

function resetForm() {
    document.getElementById('nom').value = '';
    document.getElementById('prenom').value = '';
    document.getElementById('num_tel').value = '';
    document.getElementById('email').value = '';
    document.getElementById('conditions').checked = false;

    document.getElementById('nom').classList.remove('error');
    document.getElementById('prenom').classList.remove('error');
    document.getElementById('num_tel').classList.remove('error');
    document.getElementById('email').classList.remove('error');

    document.getElementById('quantite-nuit').value = 0;
    document.getElementById('quantite-Repas-midi').value = 0;
    document.getElementById('quantite-Repas-soir').value = 0;

    calculateTotals();
}

function showError(message) {
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}