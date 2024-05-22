<!-- Formulaire permettant d'ajouter un bon de livraison -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <form action="?controller=<?= $_GET['controller'] ?>&action=ajout_bdl" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Ajout d'un Bon de livraison</h2>
                <div class="input-group">
                    <label for="sté">Mission</label>
                    <input type="text" placeholder="Mission" id="sté" name="mission" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Mois</label>
                    <input type="month" placeholder="Mois" id="sté" name="mois" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Composante</label>
                    <input type="text" placeholder="Composante" id="sté" name="composante" class="input-case">

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