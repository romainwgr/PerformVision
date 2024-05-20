

$(document).ready(function() {
    function isValidPhoneNumber(phone) {
        console.log("Vérification du numéro de téléphone:", phone);  // Pour confirmer l'appel

        var phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}$/;
        return phoneRegex.test(phone);
    }
    // Cacher toutes les étapes sauf la première au chargement
    $(".step").not("#step1").hide();

    //Pour le premier ca marche bien faut juste essayer de faire un truc lorsqu'on clique sur retour et suivant apres ca ne fait pas d'opération
    // Ensuite mettre en place les transaction? ou juste vérifier chaque formulaire et envoyer le tout a la fin?
    $(".next-btn").click(function() {
        // e.stopPropagation(); // Empêche l'événement de se propager à d'autres boutons "Suivant"

        var currentStep = $(this).closest(".step");
        var stepId = currentStep.attr('id');

        switch (stepId) {
            case "step1":
                handleStep1(currentStep);
                break;
            case "step2":
                handleStep2(currentStep);
                break;
            case "step3":
                handleStep3(currentStep);
                break;
            default:
                break;
        }




        function handleStep1(currentStep){
        
            var societe = $('#sté').val();
            var telephone = $('#phone').val();
            $('#sté').css('border', ''); 
            $('#client-error').hide(); 
            if (!isValidPhoneNumber(telephone)) {

                $('#phone').css('border', '1px solid red');
                $('#phone-error').text('Numéro de téléphone non valide.').show();
                return; 
            }
        
            var data = {
                client: societe,
                tel: telephone
            };
        
            console.log("Envoi des données:", data);
        
            $.ajax({
                url: 'index.php?controller=gestionnaire&action=is_client',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log("Réponse reçue:", response);
                    if(response.success) {
                       
                        currentStep.hide();
                        currentStep.next(".step").show();
                    } else {
                        // Afficher une indication d'erreur
                        $('#phone').css('border', ''); // Enlève la bordure rouge
                        $('#phone-error').hide(); // Cache le message d'erreur
                        currentStep.find('#sté').css('border', '1px solid red'); // Ajoute une bordure rouge
                        $('#client-error').text(response.message).show();  
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erreur AJAX:", status, error);
                    alert('Erreur lors de l\'envoi des données.');
                }
            });
        }
        function handleStep2(currentStep){

        }

    });

    $(".prev-btn").click(function() {
        var currentStep = $(this).closest(".step");
        currentStep.hide();
        currentStep.prev(".step").show();
    });
});
