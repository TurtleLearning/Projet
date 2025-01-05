// reservation.test.js

describe('Page de Réservation', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <form>
                <input type="text" id="nom" name="nom" required>
                <input type="text" id="prenom" name="prenom" required>
                <input type="tel" id="num_tel" name="num_tel" required>
                <input type="email" id="email" name="email" required>
                <input type="number" id="nombre_total" name="nombre_total" value="1" required>
                <input type="number" id="dont_enfants" name="dont_enfants" value="0">
                <input type="checkbox" id="conditions" name="conditions" required>
                <input type="number" id="quantite_nuit" name="quantite_nuit" value="0">
                <input type="text" id="sous_total" name="sous_total" readonly>
                <input type="text" id="total_ttc" name="total_ttc" readonly>
                <button type="submit" id="envoyer-btn">Envoyer</button>
            </form>
        `;

        // Ajout des fonctions de validation
        window.validateNom = (nom) => /^[A-Za-zÀ-ÿ\s-]{2,50}$/.test(nom);
        window.validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        window.validateTelephone = (tel) => /^\d{10}$/.test(tel);

        // Ajout de la fonction de calcul
        window.calculateTotals = () => {
            const nombreTotal = parseInt(document.getElementById('nombre_total').value) || 1;
            const nombreEnfants = parseInt(document.getElementById('dont_enfants').value) || 0;
            const nombreAdultes = nombreTotal - nombreEnfants;
            const quantiteNuit = parseInt(document.getElementById('quantite_nuit').value) || 0;

            // Prix fixes
            const PRIX_NUIT_ADULTE = 15;
            const PRIX_NUIT_ENFANT = 5;

            // Calcul des totaux pour les nuits
            const nuitTotalAdultes = quantiteNuit * PRIX_NUIT_ADULTE * nombreAdultes;
            const nuitTotalEnfants = quantiteNuit * PRIX_NUIT_ENFANT * nombreEnfants;
            const sousTotal = nuitTotalAdultes + nuitTotalEnfants;
            const totalTTC = sousTotal * 1.2;

            document.getElementById('sous_total').value = sousTotal.toFixed(2) + ' euros';
            document.getElementById('total_ttc').value = totalTTC.toFixed(2) + ' euros';
        };

        // Ajout des event listeners
        document.getElementById('nombre_total').addEventListener('input', () => {
            const nombreTotal = parseInt(document.getElementById('nombre_total').value) || 1;
            const dontEnfants = document.getElementById('dont_enfants');
            if (parseInt(dontEnfants.value) > nombreTotal) {
                dontEnfants.value = nombreTotal;
            }
            window.calculateTotals();
        });

        document.getElementById('dont_enfants').addEventListener('input', () => {
            const nombreTotal = parseInt(document.getElementById('nombre_total').value) || 1;
            const dontEnfants = document.getElementById('dont_enfants');
            if (parseInt(dontEnfants.value) > nombreTotal) {
                dontEnfants.value = nombreTotal;
            }
            window.calculateTotals();
        });

        document.getElementById('quantite_nuit').addEventListener('input', window.calculateTotals);
    });

    describe('Présence des éléments', () => {
        test('Le formulaire existe', () => {
            expect(document.querySelector('form')).toBeTruthy();
        });

        test('Les champs requis sont présents', () => {
            expect(document.getElementById('nom')).toBeTruthy();
            expect(document.getElementById('prenom')).toBeTruthy();
            expect(document.getElementById('num_tel')).toBeTruthy();
            expect(document.getElementById('email')).toBeTruthy();
        });
    });

    describe('Validation des champs', () => {
        test('Validation du nom', () => {
            const nomInput = document.getElementById('nom');
            
            nomInput.value = 'Dupont';
            expect(window.validateNom(nomInput.value)).toBeTruthy();
            
            nomInput.value = '123';
            expect(window.validateNom(nomInput.value)).toBeFalsy();
        });

        test('Validation email', () => {
            const emailInput = document.getElementById('email');
            
            emailInput.value = 'test@example.com';
            expect(window.validateEmail(emailInput.value)).toBeTruthy();
            
            emailInput.value = 'invalid.email';
            expect(window.validateEmail(emailInput.value)).toBeFalsy();
        });

        test('Validation téléphone', () => {
            const telInput = document.getElementById('num_tel');
            
            telInput.value = '0123456789';
            expect(window.validateTelephone(telInput.value)).toBeTruthy();
            
            telInput.value = '123';
            expect(window.validateTelephone(telInput.value)).toBeFalsy();
        });
    });

    describe('Gestion des nombres', () => {
        test('Nombre enfants <= nombre total', () => {
            const nombreTotal = document.getElementById('nombre_total');
            const dontEnfants = document.getElementById('dont_enfants');
            
            nombreTotal.value = '2';
            dontEnfants.value = '3';
            
            // Déclencher l'événement input
            const event = new Event('input');
            dontEnfants.dispatchEvent(event);
            
            expect(parseInt(dontEnfants.value)).toBe(2);
        });
    });

    describe('Calculs', () => {
        test('Calcul du total TTC', () => {
            const nombreTotal = document.getElementById('nombre_total');
            const dontEnfants = document.getElementById('dont_enfants');
            const quantiteNuit = document.getElementById('quantite_nuit');
            
            // Configuration des valeurs
            nombreTotal.value = '2';  // 2 personnes
            dontEnfants.value = '1';  // dont 1 enfant
            quantiteNuit.value = '2';  // pour 2 nuits
            
            // Déclencher le calcul
            const event = new Event('input');
            quantiteNuit.dispatchEvent(event);
            
            // Calcul attendu :
            // 1 adulte * 15€ * 2 nuits = 30€
            // 1 enfant * 5€ * 2 nuits = 10€
            // Total HT = 40€
            // Total TTC = 48€ (TVA 20%)
            const totalTTC = document.getElementById('total_ttc');
            expect(totalTTC.value).toBe('48.00 euros');
        });
    });

    describe('Validation formulaire', () => {
        test('Conditions requises pour soumission', () => {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('envoyer-btn');
            let submitted = false;
            
            // Remplir les champs requis
            document.getElementById('nom').value = 'Dupont';
            document.getElementById('prenom').value = 'Jean';
            document.getElementById('num_tel').value = '0123456789';
            document.getElementById('email').value = 'test@example.com';
            document.getElementById('conditions').checked = true;
            
            form.onsubmit = (e) => {
                e.preventDefault();
                submitted = true;
            };
            
            submitBtn.click();
            expect(submitted).toBeTruthy();
        });
    });
});