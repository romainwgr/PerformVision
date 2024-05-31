<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <h1 class="text-center gauche">Ajouter un bon de livraison</h1>

        <div class="form-container">
            <!-- Boutons de sélection des formulaires -->
            <div class="form-selector-vertical">
                <button type="button" class="btn btn-selection" onclick="showForm('form-hours')">Ajouter des
                    heures</button>
                <button type="button" class="btn btn-selection" onclick="showForm('form-half-day')">Ajouter des
                    demi-journées</button>
                <button type="button" class="btn btn-selection" onclick="showForm('form-hours-without-day')">Ajouter des
                    heures sans jour</button>
            </div>

            <!-- Formulaire pour ajouter des heures -->
            <form id="form-hours" action="?controller=prestataire&action=addBdl" method="post"
                class="add-container form-abs" style="display:none;">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="client">Client</label>
                        <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>"
                            class="input-case" required readonly>
                    </div>
                    <div class="input-group">
                        <label for="composante">Composante</label>
                        <input type="text" id="composante" name="composante"
                            value="<?= htmlspecialchars($composante ?? '') ?>" class="input-case" required readonly>
                    </div>
                </div>
                <div class="input-group">
                    <label for="mois">Mois</label>
                    <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>"
                        class="input-case" required readonly>
                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="nombre_jour">Numero Jour</label>
                        <input type="number" id="nombre_jour" name="nombre_jour" class="input-case" min="0" max="31"
                            required>
                    </div>
                    <div class="input-group">
                        <label for="nombre_heures">Nombre d'heures</label>
                        <input type="number" id="nombre_heures" name="nombre_heures" class="input-case" min="0"
                            step="0.01" required>
                    </div>
                </div>
                <div class="btns-group">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>

            <!-- Formulaire pour ajouter des demi-journées -->
            <form id="form-half-day" action="?controller=prestataire&action=addHalfDay" method="post"
                class="add-container form-abs" style="display:none;">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="client">Client</label>
                        <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>"
                            class="input-case" required readonly>
                    </div>
                    <div class="input-group">
                        <label for="composante">Composante</label>
                        <input type="text" id="composante" name="composante"
                            value="<?= htmlspecialchars($composante ?? '') ?>" class="input-case" required readonly>
                    </div>
                </div>
                <div class="input-group">
                    <label for="mois">Mois</label>
                    <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>"
                        class="input-case" required readonly>
                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="nombre_jour">Numero Jour</label>
                        <input type="number" id="nombre_jour" name="nombre_jour" class="input-case" min="0" max="31"
                            required>
                    </div>
                    <div class="input-group">
                        <label for="nombre_demi_journees">Nombre de demi-journées </label>
                        <input type="number" id="nombre_demi_journees" name="nombre_demi_journees" class="input-case"
                            min="0" step="0.5" required>
                    </div>
                </div>
                <div class="btns-group">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>

            <!-- Formulaire pour ajouter des heures sans les jours -->
            <form id="form-hours-without-day" action="?controller=prestataire&action=addHourWithoutDay" method="post"
                class="add-container form-abs" style="display:none;">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="client">Client</label>
                        <input type="text" id="client" name="client" value="<?= htmlspecialchars($client ?? '') ?>"
                            class="input-case" required readonly>
                    </div>
                    <div class="input-group">
                        <label for="composante">Composante</label>
                        <input type="text" id="composante" name="composante"
                            value="<?= htmlspecialchars($composante ?? '') ?>" class="input-case" required readonly>
                    </div>
                </div>
                <div class="input-group">
                    <label for="mois">Mois</label>
                    <input type="text" id="mois" name="mois" value="<?= htmlspecialchars($mois ?? '') ?>"
                        class="input-case" required readonly>
                </div>
                <div class="input-group">
                    <label for="nombre_heures_sans_jour">Nombre d'heures</label>
                    <input type="number" id="nombre_heures_sans_jour" name="nombre_heures_sans_jour" class="input-case"
                        min="0" step="0.01" required>
                </div>
                <div class="btns-group">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>
        </div>
        <form id="form-validate-bdl" action="?controller=prestataire&action=validerbdl" method="post" onsubmit="return confirmSubmit()">
            <div>
                <button type="submit">Valider le bon de livraison</button>
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
    document.addEventListener('DOMContentLoaded', function () {
        var selectedForm = localStorage.getItem('selectedForm');
        if (selectedForm) {
            showForm(selectedForm);
        }
    });



// Fonction pour afficher une fenêtre pop-up avec un message spécifique
function showPopup(message) {
    alert(message);
}

// Fonction pour demander une confirmation avant de soumettre le formulaire
function confirmSubmit() {
    return confirm("Êtes-vous sûr de vouloir valider le bon de livraison ? Une fois validé, plus aucun changement ne sera possible.");
}
</script>
<?php
require 'view_end.php';
?>
