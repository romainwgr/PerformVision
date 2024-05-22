<!-- Forumulaire permettant de créer une nouvelle mission -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Mission</h1>
        <form action="?controller=<?= $_GET['controller'] ?>&action=ajout_mission" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Informations mission</h2>
                <div class="input-group">
                    <label for="sté">Mission</label>
                    <input type="text" placeholder="Nom de la mission" name='mission' class="input-case" id="sté">
                </div>
                <div class="input-group">
                    <label for="sté">Société</label>
                    <input type="text" placeholder="Société" id='sté' name='client' class="input-case" id="sté">
                </div>
                <div class="input-group">
                    <label for="sté">Composante</label>
                    <input type="text" placeholder="Composante" name='composante' id='cpt' class="input-case" id="sté">

                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="type-bdl">Type de bon de livraison</label>
                        <select name="type-bdl">
                            <option selected>Type de bon de livraison</option>
                            <option value="journee">Journée</option>
                            <option value="demi-journee">Demi-journée</option>
                            <option value="heure">Heure</option>
                        </select>
                    </div>
                    <div class="input-group date">
                        <label for="date-mission">Date de début</label>
                        <input type="date" placeholder="Date de début" name="date-mission" class="input-case">
                    </div>
                </div>
                <div class="">
                    <input type="submit" class="btn btn-next width-50 ml-auto" value="Créer"></input>
                </div>
            </div>
        </form>
    </div>

</div>
<?php
require 'view_end.php';
?>