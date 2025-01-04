if (document.body.getAttribute('data-page') === 'reservation') {
    document.addEventListener('DOMContentLoaded', function() {

        // Boutons +1 / -1
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const delta = this.dataset.action === 'increase' ? 1 : -1;
                const currentValue = parseInt(input.value) || 0;
                const newValue = Math.max(0, currentValue + delta);
                input.value = newValue;
                input.dispatchEvent(new Event('input'));
            });
        });

        // Stockage constantes
        const quantiteNuit = document.getElementById('quantite_nuit');
        const quantiteRepasMidi = document.getElementById('quantite_Repas_midi');
        const quantiteRepasSoir = document.getElementById('quantite_Repas_soir');
        const imprimerBtn = document.getElementById('imprimer-btn');
        const envoyerBtn = document.getElementById('envoyer-btn');
        const reinitialiserBtn = document.getElementById('reinitialiser-btn');
        const nombreTotalInput = document.getElementById('nombre_total');
        const dontEnfantsInput = document.getElementById('dont_enfants');

        // Rafraîchissement du calcul du total dès qu'une modification est écoutée.
        quantiteNuit.addEventListener('input', calculateTotals);
        quantiteRepasMidi.addEventListener('input', calculateTotals);
        quantiteRepasSoir.addEventListener('input', calculateTotals);
        nombreTotalInput.addEventListener('input', calculateTotals);
        dontEnfantsInput.addEventListener('input', calculateTotals);

        envoyerBtn.addEventListener('click', function(event) {
            event.preventDefault();

            const formData = new FormData();

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

            // Debug
            // console.log([...formData]);

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
        });

        // Gestion du calendrier (Flatpickr)
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

        // Suppression de la date choisie avec croix rouge.
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

        function calculerNuits() {
            const debut = document.getElementById('date_debut').value;
            const fin = document.getElementById('date_fin').value;

            // Debug dates choisies.
            // console.log("Date de début:", debut);
            // console.log("Date de fin:", fin);

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

        // Gestion des calculs de prix
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
            const PRIX_REPAS_MIDI = 7;
            const PRIX_REPAS_SOIR = 15;

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

        // Gestion de la réinitialisation
        function resetForm() {
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            document.getElementById('num_tel').value = '';
            document.getElementById('email').value = '';
            document.getElementById('conditions').checked = false;
            document.getElementById('nombre_total').value ='1';
            document.getElementById('dont_enfants').value ='0';

            document.getElementById('nom').classList.remove('error');
            document.getElementById('prenom').classList.remove('error');
            document.getElementById('num_tel').classList.remove('error');
            document.getElementById('email').classList.remove('error');

            document.getElementById('quantite_nuit').value = 0;
            document.getElementById('quantite_Repas_midi').value = 0;
            document.getElementById('quantite_Repas_soir').value = 0;

            calculateTotals();
        }

        // Fonction pour formater la date
        function formatDate(dateStr) {
            const [jour, mois, annee] = dateStr.split(' / ');
            return `${annee}-${mois}-${jour}`; // Retourne au format "YYYY-MM-DD"
        }

        // Fonction impression
        imprimerBtn.addEventListener('click', () => window.print());
        if (reinitialiserBtn) {
            reinitialiserBtn.addEventListener('click', resetForm);
        } else {
            console.error("Bouton de réinitialisation non trouvé.");
            showError("Une erreur est survenue. Veuillez réessayer plus tard.");
        }
    });
}