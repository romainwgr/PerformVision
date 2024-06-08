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
    if (!/^[0-9]+/.test(adresse)) {
      return "Le nombre doit être au début.";
    }
    if (!/[0-9]+\s/.test(adresse)) {
      return "Manque l'espace après le nombre.";
    }
    if (!/[0-9]+\s\w+\s/.test(adresse)) {
      return "Manque l'espace et le type de rue.";
    }
    if (!/[0-9]+\s\w+\s[^\d\s]+/.test(adresse)) {
      return "Manque le nom de la rue.";
    }
    if (/\d+$/.test(adresse)) {
      return "L'adresse ne doit pas se terminer par un nombre.";
    }
    if (!/^[0-9]+\s[a-zA-Z]+\s[a-zA-Z\s-]+$/.test(adresse)) {
      return "Format incorrect";
    }

    const regex = /^[0-9]+(?:[ -][0-9]+)?\s\w+((?:-|\s)?[^\d\s]+)*\s[^\d\s]+/;
    if (!regex.test(adresse)) {
      return "Format incorrect.";
    }

    return true;
  }

  function isValidEmail(email) {
    const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(email);
  }

  function isValidName(name) {
    const pattern = /^[a-zA-Z\s\-.,&()']+$/;
    return pattern.test(name);
  }

  // Initialize progress bar elements
  var formSteps = document.querySelectorAll(".step");
  var progressSteps = document.querySelectorAll(".progress-step");
  var formStepsNum = 0;

  function updateFormSteps() {
    formSteps.forEach((formStep) => {
      formStep.classList.contains("form-step-active") &&
        formStep.classList.remove("form-step-active");
    });
    formSteps[formStepsNum].classList.add("form-step-active");
  }

  function updateProgressbar() {
    progressSteps.forEach((progressStep, idx) => {
      if (idx < formStepsNum + 1) {
        progressStep.classList.add("progress-step-active");
      } else {
        progressStep.classList.remove("progress-step-active");
      }
    });

    const progressActive = document.querySelectorAll(".progress-step-active");

    document.getElementById("progress").style.width =
      ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
  }

  $(".step").not("#step1").hide();

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
      case "step5":
        handleStep5(currentStep);
        break;
      default:
        break;
    }
  });

  function handleStep1(currentStep) {
    var societe = $("#sté").val();
    var telephone = $("#phone").val();

    $("#sté").removeClass("error-input");
    $("#client-error").removeClass("show").text("");
    $("#phone").removeClass("error-input");
    $("#phone-error").removeClass("show").text("");

    var hasError = false;

    if (!societe) {
      $("#sté").addClass("error-input");
      $("#client-error").text("Le nom de la société est requis.").show();
      hasError = true;
    }
    if (telephone === "") {
      $("#phone").addClass("error-input");
      $("#phone-error").text("Le numéro de téléphone est requis.").show();
      hasError = true;
    } else if (!isValidPhoneNumber(telephone)) {
      $("#phone").addClass("error-input");
      $("#phone-error").text("Numéro de téléphone non valide.").show();
      hasError = true;
    }
    if (hasError) {
      return;
    }

    var data = {
      client: societe,
      tel: telephone,
    };

    $.ajax({
      url: "index.php?controller=gestionnaire&action=is_client",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          var data = {
            client: societe,
            tel: telephone,
            step: 1,
          };

          $.ajax({
            url: "index.php?controller=gestionnaire&action=save_data",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
              if (response.success) {
                currentStep.hide();
                currentStep.next(".step").show();
                formStepsNum++;
                updateFormSteps();
                updateProgressbar();
              } else {
                $("#phone").css("border", "");
                $("#phone-error").hide();
                currentStep.find("#sté").css("border", "0.5px solid red");
                $("#client-error").text(response.message).show();
              }
            },
            error: function (xhr, status, error) {
              console.error("Erreur AJAX:", xhr, status, error);
              alert("Erreur lors de l'envoi des données.");
            },
          });
        } else {
          $("#phone").css("border", "");
          $("#phone-error").hide();
          currentStep.find("#sté").css("border", "0.5px solid red");
          $("#client-error").text(response.message).show();
        }
      },
      error: function (xhr, status, error) {
        console.error("Erreur AJAX:", status, error);
        alert("Erreur lors de l'envoi des données.");
      },
    });
  }

  function handleStep2(currentStep) {
    var composante = $("#composante").val();

    $("#composante").css("border", "");
    $("#composante-error").hide();

    if (composante === "") {
      $("#composante").css("border", "1px solid red");
      $("#composante-error").text("Le nom de la composante est requis.").show();
      return;
    }

    var data = {
      composante: composante,
      step: 2,
    };

    $.ajax({
      url: "index.php?controller=gestionnaire&action=save_data",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          currentStep.hide();
          currentStep.next(".step").show();
          formStepsNum++;
          updateFormSteps();
          updateProgressbar();
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

  function handleStep3(currentStep) {
    var adresse = $("#adresse").val();
    adresse = adresse.replace(/\s+/g, " ");

    var voie = $("#voie").val();
    var cp = $("#cp").val();
    var ville = $("#ville").val();

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
    }
    if (
      typeof voie !== "string" ||
      voie.trim() === "" ||
      !/^[a-zA-Z]+$/.test(voie)
    ) {
      $("#voie").css("border", "1px solid red");
      $("#voie-error").text("Le type de voie n'est pas valide").show();
      erreur = true;
    }
    if (!isValidCp(cp)) {
      $("#cp").css("border", "1px solid red");
      $("#cp-error").text("Le code postal n'est pas valide").show();
      erreur = true;
    }
    if (
      typeof ville !== "string" ||
      ville.trim() === "" ||
      !/^[a-zA-Z]+$/.test(ville)
    ) {
      $("#ville").css("border", "1px solid red");
      $("#ville-error").text("La ville n'est pas valide").show();
      erreur = true;
    }
    if (erreur) {
      return;
    }

    var data = {
      adresse: adresse,
      voie: voie,
      cp: cp,
      ville: ville,
      step: 3,
    };

    $.ajax({
      url: "index.php?controller=gestionnaire&action=save_data",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          currentStep.hide();
          currentStep.next(".step").show();
          formStepsNum++;
          updateFormSteps();
          updateProgressbar();
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

  function handleStep4(currentStep) {
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

    var error = false;

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

    var data2 = {
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
            step: 4,
          };

          $.ajax({
            url: "index.php?controller=gestionnaire&action=save_data",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
              if (response.success) {
                currentStep.hide();
                currentStep.next(".step").show();
                formStepsNum++;
                updateFormSteps();
                updateProgressbar();
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
  }

  function handleStep5(currentStep) {
    var selectedValues = [];
    $(".select-commercial:checked").each(function () {
      selectedValues.push($(this).val());
    });

    if (selectedValues.length === 0) {
      $("#commercial-error")
        .text("Vous devez sélectionner au moins un commercial.")
        .show();
      return;
    }

    var data = {
      idsCommerciaux: selectedValues,
      step: 5,
    };

    $.ajax({
      url: "index.php?controller=gestionnaire&action=save_data",
      type: "POST",
      data: data,
      dataType: "json",
      success: function (response) {
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
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();
  });
});
