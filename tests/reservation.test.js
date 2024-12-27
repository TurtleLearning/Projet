const { validateForm } = require('../scriptReservation'); // Assurez-vous que la fonction est exportée

describe('Validation du formulaire', () => {
    test('validateForm devrait retourner false si le nom est vide', () => {
        // Simuler un formulaire avec un nom vide
        const formData = {
            nom: '',
            prenom: 'Dupont',
            num_tel: '0123456789',
            email: 'test@example.com',
            quantiteNuit: 1,
            quantiteRepasMidi: 0,
            quantiteRepasSoir: 0,
            date_debut: '2023-10-01',
            date_fin: '2023-10-02'
        };

        // Appeler la fonction de validation
        const result = validateForm(formData);
        
        // Vérifier que le résultat est faux
        expect(result).toBe(false);
    });

    // autres tests
}); 