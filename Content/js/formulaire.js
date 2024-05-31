const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");

// page connexion
document.addEventListener("DOMContentLoaded", function () {
  if (document.querySelector(".popup")) {
    const inputs = document.querySelectorAll("input");
    const buttons = document.querySelectorAll(".desactive");
    inputs.forEach((input) => {
      input.disabled = true;
    });
    buttons.forEach((button) => {
      button.disabled = true;
    });
  }
});

// les formulaires
let formStepsNum = 0;

nextBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum++;
    updateFormSteps();
    updateProgressbar();
  });
});

prevBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();
  });
});

// barre de navigation
const searchBox = document.querySelector(".search-box");
const searchBtn = document.querySelector(".search-icon");
const cancelBtn = document.querySelector(".cancel-icon");
const searchInput = document.querySelector("input");
const searchData = document.querySelector(".search-data");
const searchForm = document.querySelector(".search_form");

searchBtn.onclick = () => {
  if (!searchBox.classList.contains("active")) {
    // Activer la boîte de recherche
    searchBox.classList.add("active");
    searchBtn.classList.add("active");
    searchInput.classList.add("active");
    cancelBtn.classList.add("active");
    searchInput.focus();
  } else if (searchInput.value !== "") {
    // Soumettre le formulaire si la boîte de recherche est déjà active et le champ de recherche n'est pas vide
    searchForm.submit();
  }

  if (searchInput.value != "") {
    var values = searchInput.value;
    searchData.classList.remove("active");
    searchData.innerHTML =
      "You just typed " +
      "<span style='font-weight: 500;'>" +
      values +
      "</span>";
  } else {
    searchData.textContent = "";
  }
};

cancelBtn.onclick = () => {
  searchBox.classList.remove("active");
  searchBtn.classList.remove("active");
  searchInput.classList.remove("active");
  cancelBtn.classList.remove("active");
  searchData.classList.toggle("active");
  searchInput.value = "";
};

// pour affiche si l'ajout a fonctionner ou non
document.addEventListener("DOMContentLoaded", function () {
  var popup = document.getElementById("messagePopup");
  var closeBtn = document.getElementsByClassName("close-btn")[0];
  var message = popup.getAttribute("data-message");

  if (message) {
    document.getElementById("popupMessage").textContent = message;
    popup.style.display = "block";
  }

  closeBtn.onclick = function () {
    popup.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == popup) {
      popup.style.display = "none";
    }
  };
});

// etape du formulaire
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

  progress.style.width =
    ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}

function closeForm() {
  const formContainer = document.getElementById("caheaffiche");
  let affiche = document.getElementById("affiche");
  formContainer.style.display = "none";
}

//  formulaire pour modifier les infos
// Récupérer la carte d'emploi et le formulaire
const jobCardLink = document.querySelector(".job-card-link");
const formContainer = document.getElementById("caheaffiche");

// Ajouter un gestionnaire d'événements pour le clic sur la carte d'emploi
jobCardLink.addEventListener("click", function (event) {
  // Empêcher le comportement par défaut du lien
  event.preventDefault();

  // Afficher ou masquer le formulaire en fonction de son état actuel
  if (formContainer.style.display === "none") {
    formContainer.style.display = "block";
  } else {
    formContainer.style.display = "none";
  }
});

// pour revenir a la page precedent qd on appuie sur l'icone de fermeture
function closeFormajout() {
  document.getElementById("close-form").addEventListener("click", function () {
    window.history.back();
  });
}

// ajax
$(document).ready(function () {
  function isValidPhoneNumber(phone) {
    console.log("Vérification du numéro de téléphone:", phone); // Pour confirmer l'appel

    var phoneRegex =
      /^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}$/;
    return phoneRegex.test(phone);
  }

  function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function sanitizeInput(input) {
    return $("<div/>").text(input).html();
  }

  // Cacher toutes les étapes sauf la première au chargement
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
      default:
        break;
    }

    function handleStep1(currentStep) {
      var societe = sanitizeInput($("#sté").val());
      var telephone = sanitizeInput($("#phone").val());
      $("#sté").css("border", "");
      $("#client-error").hide();

      if (!isValidPhoneNumber(telephone)) {
        $("#phone").css("border", "1px solid red");
        $("#phone-error").text("Numéro de téléphone non valide.").show();
        return;
      }

      var data = {
        client: societe,
        tel: telephone,
      };

      console.log("Envoi des données:", data);

      $.ajax({
        url: "index.php?controller=gestionnaire&action=is_client",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (response) {
          console.log("Réponse reçue:", response);
          if (response.success) {
            currentStep.hide();
            currentStep.next(".step").show();
          } else {
            $("#phone").css("border", ""); // Enlève la bordure rouge
            $("#phone-error").hide(); // Cache le message d'erreur
            currentStep.find("#sté").css("border", "1px solid red"); // Ajoute une bordure rouge
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
      // Implement similar validation and sanitization for step 2
    }

    function handleStep3(currentStep) {
      // Implement similar validation and sanitization for step 3
    }
  });

  $(".prev-btn").click(function () {
    var currentStep = $(this).closest(".step");
    currentStep.hide();
    currentStep.prev(".step").show();
  });
});
