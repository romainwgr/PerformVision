<!-- Formulaire permettant d'ajouter un nouveau commercial  -->

<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()">
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajouter un commercial</h1>
        <form id="ajoutcommercialForm" action="?controller=<?= $_GET['controller'] ?>&action=<?php if (isset($_GET['id'])):
              echo 'ajout_commercial_dans_mission&id=' . $_GET['id'];
          else:
              echo 'ajout_commercial';
          endif; ?>" method="post" class="form">
            <div class="form-step form-step-active">
                <h2>Informations personnelles</h2>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="prenom">Prénom: </label>
                        <input type="text" placeholder="Prénom" id="prenom" name="prenom" class="input-case">
                        <div id="prenom-error" class="error-message" style="color: red; display: none;"></div>
                    </div>
                    <div class="input-group">
                        <label for="nom">Nom: </label>
                        <input type="text" placeholder="Nom" id="nom" name="nom" class="input-case">
                        <div id="nom-error" class="error-message" style="color: red; display: none;"></div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="email-commercial">Adresse email:</label>
                    <input type="email" placeholder="Adresse email" id="email-commercial" name="email-commercial"
                        class="input-case">
                    <div id="email-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <label for="tel-commercial">Numéro de téléphone:</label>
                    <input type="telephone" placeholder="Téléphone" id="tel-commercial" name="tel-commercial"
                        class="input-case">
                    <div id="tel-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="btns-group">
                    <button type="button" class="btn width-50 ml-auto">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="Content/js/ajoutCommercial.js"></script>
<?php
require 'Views/view_end.php';
?>