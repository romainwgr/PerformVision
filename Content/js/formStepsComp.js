$(document).ready(function () {
    navigator.sendBeacon(
      "index.php?controller=gestionnaire&action=clearFormSession"
    );
  
    function isValidPhoneNumber(phone) {
      var phoneRegex =
        /^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}$/;
      return phoneRegex.test(phone);
    }
    function isValidCp(cp) {
      const cpRegex = /^[0-9]{5}$/;
  
      return cpRegex.test(cp);
    }
    function isValidAdresse(adresse) {
      // Vérifier que l'adresse commence par un nombre
      if (!/^[0-9]+/.test(adresse)) {
        return "Le nombre doit être au début.";
      }
  
      // Vérifier qu'il y a un espace après le nombre
      if (!/[0-9]+\s/.test(adresse)) {
        return "Manque l'espace après le nombre.";
      }
  
      // Vérifier qu'il y a un type de rue suivi d'un espace
      if (!/[0-9]+\s\w+\s/.test(adresse)) {
        return "Manque l'espace et le type de rue.";
      }
  
      // Vérifier qu'il y a un nom de rue après le type de rue
      if (!/[0-9]+\s\w+\s[^\d\s]+/.test(adresse)) {
        return "Manque le nom de la rue.";
      }
  
      // Vérifier que l'adresse ne se termine pas par un nombre
      if (/\d+$/.test(adresse)) {
        return "L'adresse ne doit pas se terminer par un nombre.";
      }
  
      if (!/^[0-9]+\s[a-zA-Z]+\s[a-zA-Z\s-]+$/.test(adresse)) {
        return "Format incorrect";
      }
  
      // Vérifier le format général de l'adresse
      const regex = /^[0-9]+(?:[ -][0-9]+)?\s\w+((?:-|\s)?[^\d\s]+)*\s[^\d\s]+/;
      if (!regex.test(adresse)) {
        return "Format incorrect.";
      }
  
      return true;
    }
    function isValidEmail(email) {
      // Expression régulière pour valider une adresse email
      const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
  
      if (!regex.test(email)) {
        return false;
      }
  
      return true;
    }
    function isValidName(name) {
      const pattern = /^[a-zA-Z\s\-.,&()']+$/;
      return pattern.test(name);
    }

  
    // Cacher toutes les étapes sauf la première au chargement
    $(".step").not("#step1").hide();
    console.log("salut bg")
    $(".next-btn").click(function () {
      var currentStep = $(this).closest(".step");
      var stepId = currentStep.attr("id");
  
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
        case "step4":
          handleStep4(currentStep);
          break;
        default:
          break;
      }
    });
  
    
    function handleStep1(currentStep) {
        var societe = $("#societe").val();
      var composante = $("#composante").val();
  
      $("#composante").css("border", ""); // Enlève la bordure rouge
      $("#composante-error").hide();
  
      if (composante === "") {
        $("#composante").css("border", "1px solid red");
        $("#composante-error").text("Le nom de la composante est requis.").show();
        return;
      }
  
      // Vous pouvez sauvegarder la composante ici avec un appel AJAX ou d'une autre manière.
      // Par exemple :
      // saveData({ composante: composante });
      var data = {
        societe: societe,
        composante: composante,
        step: 1,
      };
      $.ajax({
        url: "index.php?controller=gestionnaire&action=save_data2",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
          console.log("Réponse du serveur :", response);
  
          if (response.success) {
            console.log("Session mise à jour");
            currentStep.hide();
            currentStep.next(".step").show();
          } else {
            $("#composante").css("border", "1px solid red");
  
            $("#composante-error").text(response.message).show();
          }
        },
        error: function (xhr, status, error) {
          console.error("Erreur AJAX:", xhr, status, error);
          alert("Erreur lors de l'envoi des données.");
        },
      });
    }
  
    function handleStep2(currentStep) {
      // Ajoutez vos vérifications et traitements pour l'étape 3 ici
      var adresse = $("#adresse").val();
      adresse = adresse.replace(/\s+/g, " "); //SUpprimer les espaces doublons
  
      var voie = $("#voie").val();
      var cp = $("#cp").val();
      var ville = $("#ville").val();
      console.log(adresse);
      $("#adresse").css("border", "");
      $("#voie").css("border", "");
      $("#cp").css("border", "");
      $("#ville").css("border", "");
      $("#adresse-error").hide();
      $("#voie-error").hide();
      $("#cp-error").hide();
      $("#ville-error").hide();
  
      var erreur = false;
      var validationResult = isValidAdresse(adresse);
      if (typeof validationResult === "string") {
        $("#adresse").css("border", "1px solid red");
        $("#adresse-error").text(validationResult).show();
        erreur = true;
        console.log("adresse" + validationResult);
      }
      if (
        typeof voie !== "string" ||
        voie.trim() === "" ||
        !/^[a-zA-Z]+$/.test(voie)
      ) {
        $("#voie").css("border", "1px solid red");
        $("#voie-error").text("Le type de voie n'est pas valide").show();
        erreur = true;
        console.log("voie" + voie);
      }
      if (!isValidCp(cp)) {
        $("#cp").css("border", "1px solid red");
        $("#cp-error").text("Le code postal n'est pas valide").show();
        erreur = true;
        console.log("cp");
      }
      if (
        typeof ville !== "string" ||
        ville.trim() === "" ||
        !/^[a-zA-Z]+$/.test(ville)
      ) {
        $("#ville").css("border", "1px solid red");
        $("#ville-error").text("La ville n'est pas valide").show();
        erreur = true;
        console.log("ville");
      }
      if (erreur) {
        console.log("errrerur");
        return;
      }
      var data = {
        adresse: adresse,
        voie: voie,
        cp: cp,
        ville: ville,
        step: 2,
      };
      $.ajax({
        url: "index.php?controller=gestionnaire&action=save_data2",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
          console.log("Réponse du serveur :", response);
  
          if (response.success) {
            console.log("Session mise à jour");
            currentStep.hide();
            currentStep.next(".step").show();
          } else {
            $("#adresse").css("border", "1px solid red");
            $("#adresse-error").text(response.message).show();
          }
        },
        error: function (xhr, status, error) {
          console.error("Erreur AJAX:", xhr, status, error);
          alert("Erreur lors de l'envoi des données.");
        },
      });
    }
  
    function handleStep3(currentStep) {
      // $('#search-input').keyup(function() {
      //     var query = $(this).val();
      //     searchCommercials(query);
      // });
      var prenom_int = $("#prenom-int").val();
      var nom_int = $("#nom-int").val();
      var email_int = $("#mail-int").val();
      var tel_int = $("#tel-int").val();
  
      $("#prenom-int").css("border", "");
      $("#nom-int").css("border", "");
      $("#mail-int").css("border", "");
      $("#tel-int").css("border", "");
      $("#prenom-int-error").hide();
      $("#nom-int-error").hide();
      $("#mail-int-error").hide();
      $("#tel-int-error").hide();
      // Ajoutez vos vérifications et traitements pour l'étape 4 ici
      error = false;
  
      if (!isValidName(prenom_int)) {
        $("#prenom-int").css("border", "1px solid red");
        $("#prenom-int-error").text("Le prénom n'est pas valide").show();
        error = true;
      }
  
      if (!isValidName(nom_int)) {
        $("#nom-int").css("border", "1px solid red");
        $("#nom-int-error").text("Le nom n'est pas valide").show();
        error = true;
      }
  
      if (!isValidEmail(email_int)) {
        $("#email-int").css("border", "1px solid red");
        $("#email-int-error").text("L'email n'est pas valide").show();
        error = true;
      }
  
      if (!isValidPhoneNumber(tel_int)) {
        $("#tel-int").css("border", "1px solid red");
        $("#tel-int-error")
          .text("Le numéro de téléphone n'est pas valide")
          .show();
        error = true;
      }
      if (error) {
        return;
      }
      data2 = {
        mail: email_int,
      };
      $.ajax({
        url: "index.php?controller=gestionnaire&action=is_interlocuteur",
        type: "POST",
        data: data2,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            var data = {
              prenom_int: prenom_int,
              nom_int: nom_int,
              mail_int: email_int,
              tel_int: tel_int,
              step: 3,
            };
            $.ajax({
              url: "index.php?controller=gestionnaire&action=save_data2",
              type: "POST",
              data: data,
              dataType: "json",
              success: function (response) {
                console.log("Réponse du serveur :", response);
  
                if (response.success) {
                  console.log("Session mise à jour");
                  currentStep.hide();
                  currentStep.next(".step").show();
                } else {
                  $("#email-int").css("border", "1px solid red");
                  $("#email-int-error").text(response.message).show();
                }
              },
              error: function (xhr, status, error) {
                console.error("Erreur AJAX:", xhr, status, error);
                alert("Erreur lors de l'envoi des données.");
              },
            });
          } else {
            $("#email-int").css("border", "1px solid red");
            $("#email-int-error").text(response.message).show();
          }
        },
        error: function (xhr, status, error) {
          console.error("Erreur AJAX:", status, error);
          alert("Erreur lors de l'envoi des données.");
        },
      });
  
      // currentStep.hide();
      // currentStep.next(".step").show();
    }
    function handleStep4(currentStep) {
      var selectedValues = [];
      $(".select-commercial:checked").each(function () {
        selectedValues.push($(this).val());
      });
  
      // Vérifier si aucune case à cocher n'est sélectionnée
      if (selectedValues.length === 0) {
        $("#commercial-error")
          .text("Vous devez sélectionner au moins un commercial.")
          .show();
        return; // Arrêter l'exécution si aucune case n'est sélectionnée
      }
      var data = {
        idsCommerciaux: selectedValues,
        step: 4,
      };
      $.ajax({
        url: "index.php?controller=gestionnaire&action=save_data2",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
          console.log("Réponse du serveur :", response);
  
          if (response.success) {
            window.location.href =
              "index.php?controller=gestionnaire&action=view_after_save";
          } else {
            $("#commercial-error").text(response.message).show();
          }
        },
        error: function (xhr, status, error) {
          console.error("Erreur AJAX:", xhr, status, error);
          alert("Erreur lors de l'envoi des données.");
        },
      });
    }
  
    $(".prev-btn").click(function () {
      var currentStep = $(this).closest(".step");
      currentStep.hide();
      currentStep.prev(".step").show();
    });
  });
  