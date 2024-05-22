<!-- Vue de l'interlocuteur où il peut voir ses missions, y consulter ses bons de livraison et faire une demande -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class="add-container">
    <div class="dashboard-container">
        <h1>Mes Prestataires</h1>
        <?php require_once 'view_dashboard.php'; ?>
    </div>
    <div class="form-abs">
        <form action="?controller=interlocuteur&action=envoyer_email" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Une demande ? C'est juste ici !</h2>
                <div class="input-group">
                    <label for="sté">Objet</label>
                    <input type="text" id="sté" name="objet" placeholder="Objet" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Message</label>
                    <textarea id="sté" name="message" placeholder="Votre message..." class="input-case"></textarea>
                </div>
            </div>
            <div class="btns-group">
                <input type="submit" value="Envoyer" class="btn">
            </div>
        </form>

    </div>
</div>

<?php
require 'view_end.php';
?>