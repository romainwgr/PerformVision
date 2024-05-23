<!-- Formulaire permettant à l'administrateur d'ajouter un nouveau gestionnaire  -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Gestionnaire</h1>

        <form action=" ?controller=administrateur&action=ajout_gestionnaire" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Informations personnelles</h2>
                <div class="input-group">
                    <label for="sté">Prénom</label>
                    <input type="text" placeholder="Prénom" id="sté" name="prenom" class="input-case" require>
                </div>
                <div class="input-group">
                    <label for="sté">Nom</label>
                    <input type="text" placeholder="Nom" id="sté" name="nom" class="input-case" require>
                </div>
                <div class="input-group">
                    <label for="sté">Email</label>
                    <input type="email" placeholder="Adresse email" id="sté" name="email-gestionnaire"
                        class="input-case" require>

                </div>
            </div>
            <div class="btns-group">
                <input type="submit" value="Créer" class="btn">
            </div>
        </form>

    </div>
</div>
<?php
require 'view_end.php';
?>