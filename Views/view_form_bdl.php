<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <h1>Ajouter un bon de livraison</h1>

        <!-- Boutons de sélection des formulaires -->
        <div class="form-selector">
            <button type="button" onclick="showForm('form-hours')">Ajouter des heures</button>
            <button type="button" onclick="showForm('form-half-day')">Ajouter des demi-journées</button>
            <button type="button" onclick="showForm('form-hours-without-day')">Ajouter des heures sans jour</button>
        </div>

        <!-- Formulaire pour ajouter des heures -->
        <form id="form-hours" action="?controller=prestataire&action=addBdl" method="post" onsubmit="showPopup('Ajout des heures réussi !')" style="display:none;">
            <div>
                <label for="client">Client :</label>
                <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="composante">Composante :</label>
                <input type="text" id="composante" name="composante" value="<?= htmlspecialchars($composante ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="mois">Mois :</label>
                <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="nombre_jour">Numero Jour :</label>
                <input type="number" id="nombre_jour" name="nombre_jour" min="0" max="31" required>
            </div>
            <div>
                <label for="nombre_heures">Nombre d'heures :</label>
                <input type="number" id="nombre_heures" name="nombre_heures" min="0" step="0.01" required>
            </div>
            <div>
                <button type="submit">Ajouter</button>
            </div>
        </form>

        <!-- Formulaire pour ajouter des demi-journées -->
        <form id="form-half-day" action="?controller=prestataire&action=addHalfDay" method="post" onsubmit="showPopup('Ajout des demi-journées réussi !')" style="display:none;">
            <div>
                <label for="client">Client :</label>
                <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="composante">Composante :</label>
                <input type="text" id="composante" name="composante" value="<?= htmlspecialchars($composante ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="mois">Mois :</label>
                <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="nombre_jour">Numero Jour :</label>
                <input type="number" id="nombre_jour" name="nombre_jour" min="0" max="31" required>
            </div>
            <div>
                <label for="nombre_demi_journees">Nombre de demi-journées :</label>
                <input type="number" id="nombre_demi_journees" name="nombre_demi_journees" min="0" step="0.5" required>
            </div>
            <div>
                <button type="submit">Ajouter</button>
            </div>
        </form>

        <!-- Formulaire pour ajouter des heures sans les jours -->
        <form id="form-hours-without-day" action="?controller=prestataire&action=addHourWithoutDay" method="post" onsubmit="showPopup('Ajout des heures sans jour réussi !')" style="display:none;">
            <div>
                <label for="client">Client :</label>
                <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="composante">Composante :</label>
                <input type="text" id="composante" name="composante" value="<?= htmlspecialchars($composante ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="mois">Mois :</label>
                <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>" required readonly>
            </div>
            <div>
                <label for="nombre_heures_sans_jour">Nombre d'heures :</label>
                <input type="number" id="nombre_heures_sans_jour" name="nombre_heures_sans_jour" min="0" step="0.01" required>
            </div>
            <div>
                <button type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</section>

<script>
// Fonction pour afficher un formulaire spécifique et enregistrer son ID dans le stockage local
function showForm(formId) {
    document.getElementById('form-hours').style.display = 'none';
    document.getElementById('form-half-day').style.display = 'none';
    document.getElementById('form-hours-without-day').style.display = 'none';
    document.getElementById(formId).style.display = 'block';
    localStorage.setItem('selectedForm', formId);
}

// Vérifier s'il y a un formulaire sélectionné dans le stockage local lors du chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    var selectedForm = localStorage.getItem('selectedForm');
    if (selectedForm) {
        showForm(selectedForm);
    }
});

// Fonction pour afficher une fenêtre pop-up avec un message spécifique
function showPopup(message) {
    alert(message);
}
</script>

<?php
require 'view_end.php';
?>
