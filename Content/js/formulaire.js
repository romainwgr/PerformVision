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
var formVisible = false;
function closeForm() {
  document.getElementById("close-form").addEventListener("click", function () {
    window.history.back();
    if (formVisible) {
      // Revenir à la page précédente uniquement si le formulaire était visible
      window.history.back();
      formVisible = false; // Mettre à jour l'état du formulaire
    }
  });
}
function showPopup(message) {
  alert(message);
}

function closeFormajout() {
  // Sélectionne l'élément contenant le formulaire
  var formContainer = document.getElementById("caheaffiche");
  // Masque l'élément en modifiant son style
  formContainer.style.display = "none";
}
