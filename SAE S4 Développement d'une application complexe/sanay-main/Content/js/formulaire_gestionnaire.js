document.getElementById('gestionnaire-form').addEventListener('submit', function(event) {
    var prenom = document.querySelector('input[name="prenom"]');
    var nom = document.querySelector('input[name="nom"]');
    var email = document.querySelector('input[name="email-gestionnaire"]');
    
    var namePattern = /^[a-zA-Zàâçéèêëîïôûùüÿñæœ-]+$/;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Reset styles
    prenom.style.borderColor = '';
    nom.style.borderColor = '';
    email.style.borderColor = '';

    var valid = true;

    if (!namePattern.test(prenom.value) || prenom.value.length < 3) {
        prenom.style.borderColor = 'red';
        alert('Le prénom doit contenir au moins 3 caractères et ne doit pas comporter de chiffres ou de caractères spéciaux.');
        valid = false;
    }

    if (!namePattern.test(nom.value) || nom.value.length < 3) {
        nom.style.borderColor = 'red';
        alert('Le nom doit contenir au moins 3 caractères et ne doit pas comporter de chiffres ou de caractères spéciaux.');
        valid = false;
    }

    if (!emailPattern.test(email.value)) {
        email.style.borderColor = 'red';
        alert('Veuillez entrer une adresse email valide.');
        valid = false;
    }

    if (!valid) {
        event.preventDefault(); // Empêche la soumission du formulaire
    }
});