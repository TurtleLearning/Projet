document.addEventListener('DOMContentLoaded', function() {
    const quantiteNuit = document.getElementById('quantite-nuit');
    const quantiteRepasMidi = document.getElementById('quantite-Repas-midi');
    const quantiteRepasSoir = document.getElementById('quantite-Repas-soir');
    const imprimerBtn = document.getElementById('imprimer-btn');
    const envoyerBtn = document.getElementById('envoyer-btn');
    const reinitialiserBtn = document.getElementById('reinitialiser-btn');

    // quantiteNuit.addEventListener('input', calculateTotals);
    // quantiteRepasMidi.addEventListener('input', calculateTotals);
    // quantiteRepasSoir.addEventListener('input', calculateTotals);

    const inputs = [quantiteNuit, quantiteRepasMidi, quantiteRepasSoir];
    inputs.forEach(input => input.addEventListener('input', calculateTotals));
    
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
            const dateDebut = document.getElementById('date-debut').value;
            const dateFin = document.getElementById('date-fin').value;
            
            // Vérifier la disponibilité avant d'envoyer
            fetch('check_dates.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `dateDebut=${dateDebut}&dateFin=${dateFin}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.disponible) {
                    // Si disponible, procéder à l'envoi du formulaire
                    const formData = new FormData(document.forms[0]);
                    
                    const now = new Date();
                    const timestamp = now.getFullYear() + '-' + 
                                     String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                                     String(now.getDate()).padStart(2, '0') + ' ' + 
                                     String(now.getHours()).padStart(2, '0') + ':' + 
                                     String(now.getMinutes()).padStart(2, '0') + ':' + 
                                     String(now.getSeconds()).padStart(2, '0');
                    
                    formData.append('timestamp', timestamp);
                    
                    console.log("Données envoyées :", {
                        nom: document.getElementById('nom').value,
                        prenom: document.getElementById('prenom').value,
                        num_tel: document.getElementById('num_tel').value,
                        email: document.getElementById('email').value,
                        quantiteNuit: document.getElementById('quantite-nuit').value,
                        quantiteRepasMidi: document.getElementById('quantite-Repas-midi').value,
                        quantiteRepasSoir: document.getElementById('quantite-Repas-soir').value,
                        timestamp: timestamp
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
                } else {
                    alert('Ces dates ne sont pas disponibles. Veuillez en choisir d\'autres.');
                    return false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la vérification des dates.');
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

    const nombreTotal = document.getElementById('nombre-total');
    const nombreEnfants = document.getElementById('nombre-enfants');
    
    // Ajouter les écouteurs d'événements pour les nouveaux champs
    nombreTotal.addEventListener('input', function() {
        const total = parseInt(this.value) || 0;
        const enfants = parseInt(nombreEnfants.value) || 0;
        
        // Vérifier que le nombre d'enfants ne dépasse pas le total
        if (enfants > total) {
            nombreEnfants.value = total;
        }
        calculateTotals();
    });
    
    nombreEnfants.addEventListener('input', function() {
        const total = parseInt(nombreTotal.value) || 0;
        const enfants = parseInt(this.value) || 0;
        
        // Vérifier que le nombre d'enfants ne dépasse pas le total
        if (enfants > total) {
            this.value = total;
        }
        calculateTotals();
    });
});

/**
 * Calcule les totaux de la réservation en fonction des quantités sélectionnées
 * @param {Event} event - L'événement de changement de quantité
 * @returns {void}
 */
function calculateTotals(event) {
    const Nuit = parseInt(document.getElementById('quantite-nuit').value) || 0;
    const repasMidi = parseInt(document.getElementById('quantite-Repas-midi').value) || 0;
    const repasSoir = parseInt(document.getElementById('quantite-Repas-soir').value) || 0;
    
    const nombreTotal = parseInt(document.getElementById('nombre-total').value) || 1;
    const nombreEnfants = parseInt(document.getElementById('nombre-enfants').value) || 0;
    const nombreAdultes = nombreTotal - nombreEnfants;
    
    // Prix pour adultes
    const PRIX_NUIT_ADULTE = 15;
    const PRIX_REPAS_MIDI_ADULTE = 7;
    const PRIX_REPAS_SOIR_ADULTE = 15;
    
    // Prix pour enfants
    const PRIX_NUIT_ENFANT = 5;
    const PRIX_REPAS_MIDI_ENFANT = 5;
    const PRIX_REPAS_SOIR_ENFANT = 5;
    
    // Calcul des totaux pour adultes
    const nuitTotalAdultes = Nuit * PRIX_NUIT_ADULTE * nombreAdultes;
    const repasmidiTotalAdultes = repasMidi * PRIX_REPAS_MIDI_ADULTE * nombreAdultes;
    const repassoirTotalAdultes = repasSoir * PRIX_REPAS_SOIR_ADULTE * nombreAdultes;
    
    // Calcul des totaux pour enfants
    const nuitTotalEnfants = Nuit * PRIX_NUIT_ENFANT * nombreEnfants;
    const repasmidiTotalEnfants = repasMidi * PRIX_REPAS_MIDI_ENFANT * nombreEnfants;
    const repassoirTotalEnfants = repasSoir * PRIX_REPAS_SOIR_ENFANT * nombreEnfants;
    
    // Totaux combinés
    const nuitTotal = nuitTotalAdultes + nuitTotalEnfants;
    const repasmidiTotal = repasmidiTotalAdultes + repasmidiTotalEnfants;
    const repassoirTotal = repassoirTotalAdultes + repassoirTotalEnfants;
    
    document.getElementById('sous-total-nuit').value = nuitTotal.toFixed(2) + ' euros';
    document.getElementById('sous-total-repas-midi').value = repasmidiTotal.toFixed(2) + ' euros';
    document.getElementById('sous-total-repas-soir').value = repassoirTotal.toFixed(2) + ' euros';
    
    const sousTotal = nuitTotal + repasmidiTotal + repassoirTotal;
    const totalTTC = sousTotal * 1.2;
    
    document.getElementById('sous-total').value = sousTotal.toFixed(2) + ' euros';
    document.getElementById('total-ttc').value = totalTTC.toFixed(2) + ' euros';
}

/**
 * Réinitialise le formulaire de réservation
 * @returns {void}
 */
function resetForm() {
    document.getElementById('nom').value = '';
    document.getElementById('prenom').value = '';
    document.getElementById('num_tel').value = '';
    document.getElementById('email').value = '';
    document.getElementById('conditions').checked = false;
    document.getElementById('nombre-total').value ='';
    document.getElementById('nombre-enfants').value ='';

    document.getElementById('nom').classList.remove('error');
    document.getElementById('prenom').classList.remove('error');
    document.getElementById('num_tel').classList.remove('error');
    document.getElementById('email').classList.remove('error');

    document.getElementById('quantite-nuit').value = 0;
    document.getElementById('quantite-Repas-midi').value = 0;
    document.getElementById('quantite-Repas-soir').value = 0;

    document.getElementById('nombre-total').value = '1';
    document.getElementById('nombre-enfants').value = '0';

    calculateTotals();
}

/**
 * Valide le formulaire avant envoi
 * @returns {boolean} - Retourne true si le formulaire est valide, false sinon
 */
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

    if (quantiteNuit < 0 || quantiteRepasMidi < 0 || quantiteRepasSoir < 0) { /* Ces lignes sont utiles comme sécurité côté JavaScript, car même si l'interface utilise des inputs de type "number", certains navigateurs permettent de saisir des valeurs négatives ou un utilisateur pourrait modifier les valeurs via les outils de développement. */
        alert('Les quantités ne peuvent pas être négatives.');
        valid = false;
    }

    if (quantiteNuit === 0 && quantiteRepasMidi === 0 && quantiteRepasSoir === 0) { /* Cette validation est importante car elle vérifie qu'au moins un service est réservé. C'est une règle métier essentielle. */
        alert('Veuillez sélectionner au moins une nuit ou un repas.');
        valid = false;
    }

    const nombreTotal = parseInt(document.getElementById('nombre-total').value) || 0;
    const nombreEnfants = parseInt(document.getElementById('nombre-enfants').value) || 0;
    
    if (nombreTotal < 1) {
        alert('Le nombre total de personnes doit être au moins 1.');
        valid = false;
    }
    
    if (nombreEnfants > nombreTotal) {
        alert('Le nombre d\'enfants ne peut pas dépasser le nombre total de personnes.');
        valid = false;
    }

    if (valid) {
        nom.value = nom.value.trim().toUpperCase(); /* Force l'écriture du nom de famille en majuscule dans la table correspondante de la BDD. */
    }

    return valid;
}

function resetForm() {
    document.getElementById('nom').value = '';
    document.getElementById('prenom').value = '';
    document.getElementById('num_tel').value = '';
    document.getElementById('email').value = '';
    document.getElementById('conditions').checked = false;
    document.getElementById('nombre-total').value ='';
    document.getElementById('nombre-enfants').value ='';

    document.getElementById('nom').classList.remove('error');
    document.getElementById('prenom').classList.remove('error');
    document.getElementById('num_tel').classList.remove('error');
    document.getElementById('email').classList.remove('error');

    document.getElementById('quantite-nuit').value = 0;
    document.getElementById('quantite-Repas-midi').value = 0;
    document.getElementById('quantite-Repas-soir').value = 0;

    document.getElementById('nombre-total').value = '1';
    document.getElementById('nombre-enfants').value = '0';

    calculateTotals();
}

function showError(message) {
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}

// Ajouter une validation plus stricte des emails
function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}

// Ajouter une validation plus stricte des numéros de téléphone
function validatePhone(phone) {
    const regex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
    return regex.test(phone);
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Fonction pour récupérer les dates réservées
async function getReservedDates() {
    try {
        const response = await fetch('get_reserved_dates.php');
        const reservations = await response.json();
        
        // Créer un tableau de toutes les dates réservées
        let disabledDates = [];
        reservations.forEach(reservation => {
            let currentDate = new Date(reservation.date_debut);
            const endDate = new Date(reservation.date_fin);
            
            while (currentDate <= endDate) {
                disabledDates.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }
        });
        
        return disabledDates;
    } catch (error) {
        console.error('Erreur lors de la récupération des dates réservées:', error);
        return [];
    }
}

async function initializeCalendars() {
    const calendarConfig = {
        locale: "fr",
        minDate: "today",
        dateFormat: "d/m/Y",
        onChange: function(selectedDates, dateStr, instance) {
            const container = instance.element.closest('.date-input-container');
            container.classList.toggle('has-value', dateStr !== '');
            
            if (instance.element.id === 'date-debut' && selectedDates[0]) {
                const dateFin = document.querySelector("#date-fin")._flatpickr;
                dateFin.set('minDate', selectedDates[0]);
            }
        }
    };
    
    flatpickr("#date-debut", calendarConfig);
    flatpickr("#date-fin", calendarConfig);
}

function initializeEmptyCalendars() {
    const calendarConfig = {
        locale: "fr",
        minDate: "today",
        dateFormat: "d/m/Y",
        onValueUpdate: calculerNuits
    };
    
    initializeCalendarsWithConfig(calendarConfig);
}

function initializeCalendarsWithDates(disabledDates) {
    const calendarConfig = {
        locale: "fr",
        minDate: "today",
        dateFormat: "d/m/Y",
        disable: disabledDates,
        onValueUpdate: calculerNuits
    };
    
    initializeCalendarsWithConfig(calendarConfig);
}

function initializeCalendarsWithConfig(config) {
    // Initialiser date début
    flatpickr("#date-debut", {
        ...config,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates[0]) {
                const dateFin = document.querySelector("#date-fin")._flatpickr;
                dateFin.set('minDate', selectedDates[0]);
                
                const container = instance.element.closest('.date-input-container');
                container.classList.toggle('has-value', dateStr !== '');
            }
        }
    });

    // Initialiser date fin
    flatpickr("#date-fin", {
        ...config,
        onChange: function(selectedDates, dateStr, instance) {
            const container = instance.element.closest('.date-input-container');
            container.classList.toggle('has-value', dateStr !== '');
        }
    });
}

function convertFrenchDateToISO(dateStr) {
    const [day, month, year] = dateStr.split('/').map(num => num.trim());
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
}

// Appeler l'initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    initializeCalendars();
    // ... reste du code existant ...
});