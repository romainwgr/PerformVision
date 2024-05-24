<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>

<div class="add-container" id="affiche">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Société</h1>
        <form action="?controller=<?= $_GET['controller'] ?>&action=ajout_client" method="post" class="form">
            <!-- Progress bar -->
            <div class="progressbar">
                <div class="progress" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Société"></div>
                <div class="progress-step" data-title="Composante"></div>
                <div class="progress-step" data-title="Adresse"></div>
                <div class="progress-step" data-title="Interlocuteur"></div>
                <div class="progress-step" data-title="Commercial"></div>
            </div>

            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Informations société</h2>
                <div class="input-group">
                    <label for="sté">Société</label>
                    <input type="text" placeholder="Société" id="sté" name="client" class="input-case">
                </div>
                <div class="input-group">
                    <label for="tel">Numéro de téléphone</label>
                    <input type="tel" placeholder="Numéro de téléphone" name="tel" class="input-case"
                        autocomplete="tel">
                </div>
                <div class="">
                    <a href="#" class="btn btn-next width-50 ml-auto">Suivant</a>
                </div>
            </div>

            <div class="form-step">
                <h2>Informations composante</h2>
                <div class="input-group">
                    <label for="mission">Nom de la mission</label>
                    <input type="text" placeholder="Nom de la mission" name="mission" class="input-case">
                </div>
                <div class="input-group">
                    <label for="composante">Composante</label>
                    <input type="text" placeholder="Composante" name="composante" class="input-case">
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
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Précedent</a>
                    <a href="#" class="btn btn-next">Suivant</a>
                </div>
            </div>

            <div class="form-step">
                <h2>Adresse</h2>
                <div class="form-names">
                    <div class="input-group">
                        <label for="numero-voie">Numéro de voie</label>
                        <input type="number" placeholder="Numéro de voie" name="numero-voie"
                            class="input-case form-num-voie">
                    </div>
                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="type-voie">Type de voie</label>
                        <input type="text" placeholder="Type de voie" name="type-voie"
                            class="input-case form-type-voie">
                    </div>
                    <div class="input-group">
                        <label for="nom-voie">Nom de voie</label>
                        <input type="text" placeholder="Nom de voie" name="nom-voie" class="input-case form-nom-voie">
                    </div>
                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="cp">Code postal</label>
                        <input type="number" placeholder="Code postal" name="cp" class="input-case form-cp">
                    </div>
                    <div class="input-group">
                        <label for="ville">Ville</label>
                        <input type="text" placeholder="Ville" name="ville" class="input-case form-ville">
                    </div>
                </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Précedent</a>
                    <a href="#" class="btn btn-next">Suivant</a>
                </div>
            </div>

            <div class="form-step">
                <h2>Informations interlocuteur</h2>
                <div class="form-names">
                    <div class="input-group">
                        <label for="prenom-interlocuteur">Prénom</label>
                        <input type="text" placeholder="Prénom" name="prenom-interlocuteur" class="input-case">
                    </div>
                </div>
                <div class="form-names">
                    <div class="input-group">
                        <label for="nom-interlocuteur">Nom</label>
                        <input type="text" placeholder="Nom" name="nom-interlocuteur" class="input-case">
                    </div>
                </div>
                <div class="form-names">
                    <div class="input-group">
                        <label for="email-interlocuteur">Adresse email</label>
                        <input type="email" placeholder="Adresse email" name="email-interlocuteur" class="input-case">
                    </div>
                </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Précedent</a>
                    <a href="#" class="btn btn-next">Suivant</a>
                </div>
            </div>

            <div class="form-step">
                <h2>Informations commercial</h2>
                <div class="form-names">
                    <div class="input-group">
                        <label for="prenom-commercial">Prénom</label>
                        <input type="text" placeholder="Prénom" name="prenom-commercial" class="input-case">
                    </div>
                </div>
                <div class="form-names">
                    <div class="input-group">
                        <label for="nom-commercial">Nom</label>
                        <input type="text" placeholder="Nom" name="nom-commercial" class="input-case">
                    </div>
                </div>
                <div class="form-names">
                    <div class="input-group">
                        <label for="email-commercial">Adresse email</label>
                        <input type="email" placeholder="Adresse email" name="email-commercial" class="input-case">
                    </div>
                </div>
                <div class="btns-group">
                    <a href="#" class="btn btn-prev">Précedent</a>
                    <input type="submit" value="Créer" class="btn">
                </div>
            </div>
        </form>
    </div>
</div>
<?php
require 'Views/view_end.php';
?>