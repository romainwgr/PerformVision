<!-- Formulaire permettant d'ajouter un nouveau prestataire  -->

<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeForm()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajouter un prestataire</h1>
        <form id="ajoutPrestataireForm" action="?controller=<?= $_GET['controller'] ?>&action=<?php if (isset($_GET['id'])):
              echo 'ajout_prestataire_dans_mission&id=' . $_GET['id'];
          else:
              echo 'ajout_prestataire';
          endif; ?>" method="post" class="form">
            <div class="form-step form-step-active">
                <h2>Informations personnelles</h2>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <!-- <label for="nom">Nom: </label> -->
                        <input type="text" placeholder="Nom" id="nom" name="nom" class="input-case">
                        <div id="nom-error" class="error-message" style="color: red; display: none;"></div>
                    </div>
                    <div class="input-group">
                        <!-- <label for="prenom">Prénom: </label> -->
                        <input type="text" placeholder="Prénom" id="prenom" name="prenom" class="input-case">
                        <div id="prenom-error" class="error-message" style="color: red; display: none;"></div>
                    </div>

                </div>
                <div class="input-group">
                    <label for="email-prestataire">Adresse email:</label>
                    <input type="email" placeholder="Adresse email" id="email-prestataire" name="email-prestataire"
                        class="input-case">
                    <div id="email-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <label for="tel-prestataire">Numéro de téléphone:</label>
                    <input type="telephone" placeholder="Téléphone" id="tel-prestataire" name="tel-prestataire"
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
<?php
require 'Views/view_end.php';
?>

<!-- Formulaire permettant d'ajouter un nouveau prestataire  -->

<!-- <?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID --
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Prestataire</h1>
        <form action="?controller=<?= $_GET['controller'] ?>&action=<?php if (isset($_GET['id'])):
              echo 'ajout_prestataire_dans_mission&id=' . $_GET['id'];
          else:
              echo 'ajout_prestataire';
          endif; ?>" method="post" class="form">
            <!-- Progress bar --
            <?php if (!isset($_GET['id'])): ?>
                <div class="progressbar">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step progress-step-active" data-title="Personnelles"></div>
                    <div class="progress-step" data-title="Professionnelles"></div>
                </div>
            <?php endif; ?>

            <!-- Steps --
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
                    <label for="sté">Adresse email</label>
                    <input type="email" placeholder="Adresse email" name='email-commercial' id='sté' class="input-case"
                        require>
                </div>
                <?php if (!isset($_GET['id'])): ?>
                    <div class="">
                        <a href="#" class="btn btn-next width-50 ml-auto">Suivant</a>
                    </div>
                <?php else: ?>
                    <div class="btns-group">
                        <input type="submit" value="Créer" class="btn">
                    </div>
                <?php endif; ?>


            </div>

            <?php if (!isset($_GET['id'])): ?>
                <div class="form-step">
                    <h2>Informations professionnelles</h2>
                    <?php if (!isset($_GET['id'])): ?>
                        <div class="input-group">
                            <label for="mission">Société</label>
                            <input type="text" placeholder="Société" name="client" id='sté' class="input-case" require>
                        </div>
                    <?php else: ?>
                        <div class="input-group">
                            <label for="Nom mission">Société</label>
                            <input type="text" placeholder="Nom mission" name="mission" id='sté' class="input-case" require>
                        </div>
                    <?php endif; ?>
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
?> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="Content/js/ajoutPrestataire.js"></script>
<?php
require 'Views/view_end.php';
?>