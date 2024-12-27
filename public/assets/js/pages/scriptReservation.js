// Ne s'exécute que sur la page reservation
if (document.body.getAttribute('data-page') === 'reservation') {
    document.addEventListener('DOMContentLoaded', function() {
        const quantiteNuit = document.getElementById('quantite_nuit');
        const quantiteRepasMidi = document.getElementById('quantite_Repas_midi');
        const quantiteRepasSoir = document.getElementById('quantite_Repas_soir');
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
            event.preventDefault();
            if (validateForm()) {
                const formData = new FormData();
                
                // Utiliser les mêmes noms que ceux attendus par le PHP
                formData.append('nom', document.getElementById('nom').value);
                formData.append('prenom', document.getElementById('prenom').value);
                formData.append('num_tel', document.getElementById('num_tel').value);
                formData.append('email', document.getElementById('email').value);
                formData.append('quantite_nuit', document.getElementById('quantite_nuit').value);
                formData.append('quantite_repas_midi', document.getElementById('quantite_Repas_midi').value);
                formData.append('quantite_repas_soir', document.getElementById('quantite_Repas_soir').value);
                formData.append('nombre_total', document.getElementById('nombre_total').value);
                formData.append('dont_enfants', document.getElementById('dont_enfants').value);
                formData.append('date_debut', formatDate(document.getElementById('date_debut').value));
                formData.append('date_fin', formatDate(document.getElementById('date_fin').value));

                // Enlever "euros" du total_ttc
                const totalTTC = document.getElementById('total_ttc').value.replace(' euros', '');
                formData.append('total_ttc', totalTTC);
        
                console.log([...formData]); // Affiche le contenu de formData dans la console
        
                fetch('reservation.php', {
                    method: 'POST',
                    body: formData
                })
                
                .then(response => response.text())
                .then(data => {
                    if (data.includes("réussie")) {
                        alert('Réservation effectuée avec succès. Vous recevrez sous-peu un récapitulatif de votre commande !');
                        window.location.href = 'index.php';
                    } else {
                        alert('Erreur : ' + data); // Affiche l'erreur
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'envoi des données.');
                });
            }
        });
        
        // Initialisation du calendrier
        const dateDebut = flatpickr("#date_debut", {
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

        const dateFin = flatpickr("#date_fin", {
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
            const debut = document.getElementById('date_debut').value;
            const fin = document.getElementById('date_fin').value;
            
            if (debut && fin) {
                
                const [jourDebut, moisDebut, anneeDebut] = debut.split(' / ');
                const [jourFin, moisFin, anneeFin] = fin.split(' / ');
                
                const dateDebut = new Date(anneeDebut, moisDebut - 1, jourDebut);
                const dateFin = new Date(anneeFin, moisFin - 1, jourFin);
                
                const diffTime = Math.abs(dateFin - dateDebut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                document.getElementById('quantite_nuit').value = diffDays;
                calculateTotals();
            }
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
                    document.getElementById('quantite_nuit').value = '0';
                    calculateTotals();
                }
            });
        });

        const nombreTotal = document.getElementById('nombre_total');
        const nombreEnfants = document.getElementById('dont_enfants');
        
        // Ajouter les écouteurs d'événements pour les nouveaux champs
        nombreTotal.addEventListener('input', function() {
            const total = parseInt(this.value) || 0;
            let enfants = parseInt(nombreEnfants.value) || 0;
            
            // Vérifier que le nombre d'enfants ne dépasse pas le total
            if (enfants > total) {
                nombreEnfants.value = total;
            }
            calculateTotals();
        });
        
        nombreEnfants.addEventListener('input', function() {
            const total = parseInt(nombreTotal.value) || 0;
            let enfants = parseInt(this.value) || 0;
            
            // Vérifier que le nombre d'enfants ne dépasse pas le total
            if (enfants > total) {
                this.value = total;
            }
            calculateTotals();
        });
    });

    function calculateTotals() {
        const Nuit = parseInt(document.getElementById('quantite_nuit').value) || 0;
        const repasMidi = parseInt(document.getElementById('quantite_Repas_midi').value) || 0;
        const repasSoir = parseInt(document.getElementById('quantite_Repas_soir').value) || 0;
        
        const nombreTotal = parseInt(document.getElementById('nombre_total').value) || 1;
        let nombreEnfants = parseInt(document.getElementById('dont_enfants').value) || 0;
        const nombreAdultes = nombreTotal - nombreEnfants;
        
        // Prix fixes
        const PRIX_NUIT_ADULTE = 15;
        const PRIX_NUIT_ENFANT = 5;
        const PRIX_REPAS_MIDI = 7;  // Prix fixe par repas
        const PRIX_REPAS_SOIR = 15; // Prix fixe par repas
        
        // Calcul des totaux pour les nuits
        const nuitTotalAdultes = Nuit * PRIX_NUIT_ADULTE * nombreAdultes;
        const nuitTotalEnfants = Nuit * PRIX_NUIT_ENFANT * nombreEnfants;
        const nuitTotal = nuitTotalAdultes + nuitTotalEnfants;
        
        // Calcul des totaux pour les repas (prix fixe * quantité)
        const repasmidiTotal = repasMidi * PRIX_REPAS_MIDI;
        const repassoirTotal = repasSoir * PRIX_REPAS_SOIR;
        
        // Mise à jour des affichages
        document.getElementById('sous_total_nuit').value = nuitTotal.toFixed(2) + ' euros';
        document.getElementById('sous_total_repas_midi').value = repasmidiTotal.toFixed(2) + ' euros';
        document.getElementById('sous_total_repas_soir').value = repassoirTotal.toFixed(2) + ' euros';
        
        const sousTotal = nuitTotal + repasmidiTotal + repassoirTotal;
        const totalTTC = sousTotal * 1.2;
        
        document.getElementById('sous_total').value = sousTotal.toFixed(2) + ' euros';
        document.getElementById('total_ttc').value = totalTTC.toFixed(2) + ' euros';
    }

    function validateForm() {
        const nom = document.getElementById('nom');
        const email = document.getElementById('email');
        const num_tel = document.getElementById('num_tel');
        const conditions = document.getElementById('conditions');

        let valid = true;

        // Validation : le champ "nom" n'est pas vide.
        if (nom.value.trim() === '') {
            nom.classList.add('error');
            alert('Le champ Nom est obligatoire.');
            valid = false;
        } else {
            nom.classList.remove('error');
        }
        
        // Validation : le numéro de téléphone est dans le format attendu, à 10 chiffres.
        if (num_tel.value.trim() === '' || !/^\d{10}$/.test(num_tel.value)) {
            num_tel.classList.add('error');
            alert('Veuillez entrer un numéro de téléphone valide, contenant uniquement 10 chiffres.');
            valid = false;
        } else {
            num_tel.classList.remove('error');
        }

        // Validation : l'adresse mail doit utiliser une expression régulière
        if (email.value.trim() === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            email.classList.add('error');
            alert('Veuillez entrer un email valide.');
            valid = false;
        } else {
            email.classList.remove('error');
        }

        // Validation : Les conditions générales doivent être acceptées.
        if (!conditions.checked) {
            alert('Vous devez accepter les conditions générales.');
            valid = false;
        }

        const quantiteNuit = parseInt(document.getElementById('quantite_nuit').value);
        const quantiteRepasMidi = parseInt(document.getElementById('quantite_Repas_midi').value);
        const quantiteRepasSoir = parseInt(document.getElementById('quantite_Repas_soir').value);

        if (quantiteNuit < 0 || quantiteRepasMidi < 0 || quantiteRepasSoir < 0) {
            alert('Les quantités ne peuvent pas être négatives.');
            valid = false;
        }

        if (quantiteNuit === 0 && quantiteRepasMidi === 0 && quantiteRepasSoir === 0) {
            alert('Veuillez sélectionner au moins une nuit ou un repas.');
            valid = false;
        }

        const nombreTotal = parseInt(document.getElementById('nombre_total').value) || 0;
        let nombreEnfants = parseInt(document.getElementById('dont_enfants').value) || 0;
        
        if (nombreTotal < 1) {
            alert('Le nombre total de personnes doit être au moins 1.');
            valid = false;
        }
        
        if (nombreEnfants > nombreTotal) {
            alert('Le nombre d\'enfants ne peut pas dépasser le nombre total de personnes.');
            valid = false;
        }

        if (nombreEnfants < 0) {
            alert('Le nombre d\'enfants ne peut pas être négatif');
            valid = false;
        }

        // Validation : la date de début doit être avant la date de fin.
        const dateDebut = new Date(document.getElementById('date_debut').value);
        const dateFin = new Date(document.getElementById('date_fin').value);

        if (dateDebut >= dateFin) {
            alert('La date de départ doit être postérieure à la date d\'arrivée.');
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
        document.getElementById('nombre_total').value ='';
        document.getElementById('dont_enfants').value ='';

        document.getElementById('nom').classList.remove('error');
        document.getElementById('prenom').classList.remove('error');
        document.getElementById('num_tel').classList.remove('error');
        document.getElementById('email').classList.remove('error');

        document.getElementById('quantite_nuit').value = 0;
        document.getElementById('quantite_Repas_midi').value = 0;
        document.getElementById('quantite_Repas_soir').value = 0;

        document.getElementById('nombre_total').value = '1';
        document.getElementById('dont_enfants').value = '0';

        calculateTotals();
    }

    function showError(message) {
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
    }

    // Fonction pour formater la date
    function formatDate(dateStr) {
        const [jour, mois, annee] = dateStr.split(' / ');
        return `${annee}-${mois}-${jour}`; // Retourne au format "YYYY-MM-DD"
    }

    // Bouton pour ajouter ou diminuer quantité dans la
    function changeQuantity(id, delta) {
        const input = document.getElementById(id);
        let currentValue = parseInt(input.value) || 0;
        currentValue += delta;

        // S'assurer que la valeur ne soit pas inférieure à 0
        if (currentValue < 0) {
            currentValue = 0;
        }

        input.value = currentValue;
        calculateTotals(); // Recalculer les totaux après modification
    }
}