<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <h1 class="text-center gauche">Ajouter un bon de livraison</h1>

        <div class="form-container">
            <form id="form-add-bdl" action="?controller=prestataire&action=addBdl" method="post"
                class="add-container form-abs">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="client">Client</label>
                        <select id="client" name="client" class="input-case" required onchange="loadComposantes()">
                            <option value="">Sélectionner un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= htmlspecialchars($client['id_client']) ?>">
                                    <?= htmlspecialchars($client['nom_client']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="composante">Composante</label>
                        <select id="composante" name="composante" class="input-case" required
                            onchange="loadInterlocuteurs()">
                            <option value="">Sélectionner une composante</option>
                            <!-- Les options seront ajoutées dynamiquement ici -->
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="interlocuteur">Interlocuteur</label>
                        <select id="interlocuteur" name="interlocuteur" class="input-case" required>
                            <option value="">Sélectionner un interlocuteur</option>
                            <!-- Les options seront ajoutées dynamiquement ici -->
                        </select>
                    </div>
                </div>
                <!-- Ajoutez les autres champs du formulaire ici -->
                <div class="btns-group">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Fonction pour charger les composantes en fonction du client sélectionné
    function loadComposantes() {
        var clientId = document.getElementById('client').value;
        var composanteSelect = document.getElementById('composante');
        var interlocuteurSelect = document.getElementById('interlocuteur');

        // Réinitialiser les options de composante et d'interlocuteur
        composanteSelect.innerHTML = '<option value="">Sélectionner une composante</option>';
        interlocuteurSelect.innerHTML = '<option value="">Sélectionner un interlocuteur</option>';

        if (clientId) {
            // Effectuer une requête AJAX pour obtenir les composantes
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?controller=prestataire&action=getComposantes&id_client=' + clientId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var composantes = JSON.parse(xhr.responseText);
                    composantes.forEach(function (composante) {
                        var option = document.createElement('option');
                        option.value = composante.id_composante;
                        option.textContent = composante.nom_composante;
                        composanteSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    }

    // Fonction pour charger les interlocuteurs en fonction de la composante sélectionnée
    function loadInterlocuteurs() {
        var composanteId = document.getElementById('composante').value;
        var interlocuteurSelect = document.getElementById('interlocuteur');

        // Réinitialiser les options d'interlocuteur
        interlocuteurSelect.innerHTML = '<option value="">Sélectionner un interlocuteur</option>';

        if (composanteId) {
            // Effectuer une requête AJAX pour obtenir les interlocuteurs
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?controller=prestataire&action=getInterlocuteurs&id_composante=' + composanteId, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var interlocuteurs = JSON.parse(xhr.responseText);
                    interlocuteurs.forEach(function (interlocuteur) {
                        var option = document.createElement('option');
                        option.value = interlocuteur.id_personne;
                        option.textContent = interlocuteur.nom + ' ' + interlocuteur.prenom;
                        interlocuteurSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    }

    // Fonction pour afficher un formulaire spécifique et enregistrer son ID dans le stockage local
    function showForm(formId) {
        document.getElementById('form-hours').style.display = 'none';
        document.getElementById('form-half-day').style.display = 'none';
        document.getElementById('form-hours-without-day').style.display = 'block';
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