$(document).ready(function() {
    console.log('debut');

    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhoneNumber(phone) {
        console.log("Vérification du numéro de téléphone:", phone);  // Pour confirmer l'appel
        var phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}$/;
        return phoneRegex.test(phone);
    }

    $('.btn').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        // Récupérer les valeurs des champs
        var prenom = $('#prenom').val();
        var nom = $('#nom').val();
        var email = $('#email-commercial').val();
        var tel = $('#tel-commercial').val();

        // Réinitialiser les bordures et les messages d'erreur
        $('.input-case').css('border', '');
        $('.error-message').hide();

        // Valider les champs
        var isValid = true;

        if (prenom.trim() === '') {
            $('#prenom').css('border', '1px solid red');
            $('#prenom-error').text('Le prénom est requis.').show();
            isValid = false;
        }

        if (nom.trim() === '') {
            $('#nom').css('border', '1px solid red');
            $('#nom-error').text('Le nom est requis.').show();
            isValid = false;
        }

        if (!isValidEmail(email)) {
            $('#email-commercial').css('border', '1px solid red');
            $('#email-error').text('Adresse email non valide.').show();
            isValid = false;
        }

        if (!isValidPhoneNumber(tel)) {
            $('#tel-commercial').css('border', '1px solid red');
            $('#tel-error').text('Numéro de téléphone non valide.').show();
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        var data = {
            prenom: prenom,
            nom: nom,
            email: email,
            tel: tel
        };

        console.log("Envoi des données:", data);

        $.ajax({
            url: 'index.php?controller=gestionnaire&action=ajout_commercial',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log("Réponse reçue:", response);
                if (response.success) {
                    window.location.href = response.url;


                    // Rediriger ou effectuer une autre action
                } else {
                    if (response.field === 'email') {
                        $('#email-commercial').css('border', '1px solid red');
                        $('#email-error').text(response.message).show();
                    } else if (response.field === 'tel') {
                        $('#tel-commercial').css('border', '1px solid red');
                        $('#tel-error').text(response.message).show();
                    } else {
                        alert(response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX:", status, error);
                alert('Erreur lors de l\'envoi des données.');
            }
        });
    });
});
