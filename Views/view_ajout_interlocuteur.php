<!-- Formulaire permettant l'ajout de nouvel interlocuteur -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Interlocuteur Client</h1>
        <form action="?controller=<?= $_GET['controller'] ?>&action=ajout_interlocuteur_dans_composante<?php
          if (isset($_GET['id'])):
              echo '&id-composante=' . $_GET['id'];
          endif;
          if (isset($_GET['id-client'])):
              echo '&id-client=' . $_GET['id-client'];
          endif;
          ?>" method="post" class="form">
            <!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Personnelles"></div>
                <div class="progress-step" data-title="Professionnelles"></div>
            </div>

            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Informations personnelles</h2>
                <div class="input-group">
                    <label for="sté">Prénom</label>
                    <input type="text" placeholder="Prénom" id="sté" name="prenom" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Nom</label>
                    <input type="text" placeholder="Nom" id="sté" name="nom" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Adresse email</label>
                    <input type="email" placeholder="Adresse email" name='email-commercial' id='sté' class="input-case">
                </div>
                <div class="">
                    <a href="#" class="btn btn-next width-50 ml-auto">Suivant</a>
                </div>
            </div>

            <?php if (!isset($_GET['id'])): ?>
                <div class="form-step">
                    <h2>Informations professionnelles</h2>
                    <div class="input-group">
                        <label for="mission">Composante</label>
                        <input type="text" placeholder="Composante" name="composante" class="input-case">
                    </div>
                    <div class="btns-group">
                        <a href="#" class="btn btn-prev">Précedent</a>
                        <input type="submit" value="Créer" class="btn">
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?php
require 'view_end.php';
?>