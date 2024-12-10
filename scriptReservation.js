document.addEventListener('DOMContentLoaded', function() {
    const quantiteNuit = document.getElementById('quantite-nuit');
    const quantiteRepasMidi = document.getElementById('quantite-Repas-midi');
    const quantiteRepasSoir = document.getElementById('quantite-Repas-soir');
    const imprimerBtn = document.getElementById('imprimer-btn');
    const envoyerBtn = document.getElementById('envoyer-btn');
    const reinitialiserBtn = document.getElementById('reinitialiser-btn');

    quantiteNuit.addEventListener('input', calculateTotals);
    quantiteRepasMidi.addEventListener('input', calculateTotals);
    quantiteRepasSoir.addEventListener('input', calculateTotals);
    
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
                if (data.includes("réussie")) {
                    alert('Réservation effectuée avec succès !');
                    window.location.href = 'index.html';
                } else {
                    alert('Erreur : ' + data);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'envoi des données.');
            });
        }
    });
    
    // Initialisation du calendrier
    const dateDebut = flatpickr("#date-debut", {
        locale: "fr",
        minDate: "today",
        dateFormat: "d / m / Y",
        onChange: function(selectedDates, dateStr, instance) {
            const container = instance.element.closest('.date-input-container');
            container.classList.toggle('has-value', dateStr !== '');
            dateFin.set('minDate', dateStr);
            calculerNuits();
        }
    });

    const dateFin = flatpickr("#date-fin", {
        locale: "fr",
        minDate: "today",
        dateFormat: "d / m / Y",
        onChange: function(selectedDates, dateStr, instance) {
            const container = instance.element.closest('.date-input-container');
            container.classList.toggle('has-value', dateStr !== '');
            calculerNuits();
        }
    });

    function calculerNuits() {
        const debut = document.getElementById('date-debut').value;
        const fin = document.getElementById('date-fin').value;
        
        if (debut && fin) {
            
            const [jourDebut, moisDebut, anneeDebut] = debut.split(' / ');
            const [jourFin, moisFin, anneeFin] = fin.split(' / ');
            
            const dateDebut = new Date(anneeDebut, moisDebut - 1, jourDebut);
            const dateFin = new Date(anneeFin, moisFin - 1, jourFin);
            
            const diffTime = Math.abs(dateFin - dateDebut);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            document.getElementById('quantite-nuit').value = diffDays;
            calculateTotals();
        }
    }

    function verifierDisponibilite(dateDebut, dateFin) {
        fetch('check_dates.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `dateDebut=${dateDebut}&dateFin=${dateFin}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.disponible) {
                alert('Ces dates ne sont pas disponibles. Veuillez en choisir d\'autres.');
                document.getElementById('date-debut').value = '';
                document.getElementById('date-fin').value = '';
                document.getElementById('quantite-nuit').value = '0';
                calculateTotals();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    document.querySelectorAll('.clear-date').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input) {
                const fp = input._flatpickr;
                if (fp) {
                    fp.clear();
                }
                input.closest('.date-input-container').classList.remove('has-value');
                document.getElementById('quantite-nuit').value = '0';
                calculateTotals();
            }
        });
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

    const quantiteNuit = parseInt(document.getElementById('quantite-nuit').value);
    const quantiteRepasMidi = parseInt(document.getElementById('quantite-Repas-midi').value);
    const quantiteRepasSoir = parseInt(document.getElementById('quantite-Repas-soir').value);

    if (quantiteNuit < 0 || quantiteRepasMidi < 0 || quantiteRepasSoir < 0) {
        alert('Les quantités ne peuvent pas être négatives.');
        valid = false;
    }

    if (quantiteNuit === 0 && quantiteRepasMidi === 0 && quantiteRepasSoir === 0) {
        alert('Veuillez sélectionner au moins une nuit ou un repas.');
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