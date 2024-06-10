<!-- Formulaire permettant d'ajouter une autre composante -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>

<div class="add-container">
    <div class="form-abs">
        <h1 class="text-center">Ajout Composante</h1>
        <div class="step form-step form-step-active" id="step1">
            <h2>Informations composante</h2>
            <input type="hidden" name="societe" value="<?= $client ?>" id="societe">
            <!-- Nom composante -->
            <div class="input-group">
                <input type="text" placeholder="Composante" name='composante' class="input-case" id="composante">
                <!-- Message d'erreur -->
                <div id="composante-error" class="error-message" style="color: red; display: none;"></div>
            </div>
            <button type="button" class="next-btn btn btn-next" data-next="step2">Suivant</button>
        </div>
        <div class="step" id="step2" style="display:none;">
            <h2>Adresse</h2>
            <div class="form-address">
                <div class="input-group">
                    <!-- Adresse -->
                    <input type="text" placeholder="Adresse" name=adresse class="input-case" id="adresse">
                    <!-- Message d'erreur -->
                    <div id="adresse-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <!-- Type de voie -->
                    <input type="text" placeholder="Type de voie" name="type-voie" class="input-case form-type-voie"
                        id="voie">
                    <!-- Message d'erreur -->
                    <div id="voie-error" class="error-message" style="color: red; display: none;"></div>
                </div>
            </div>
            <div class="form-address">
                <div class="input-group">
                    <!-- Code postal -->
                    <input type="text" placeholder="Code postal" name="cp" class="input-case form-cp" id="cp">
                    <!-- Message d'erreur -->
                    <div id="cp-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <!-- Ville -->
                    <input type="text" placeholder="Ville" name="ville" class="input-case form-ville" id="ville">
                    <!-- Message d'erreur -->
                    <div id="ville-error" class="error-message" style="color: red; display: none;"></div>
                </div>
            </div>
            <button type="button" class="prev-btn btn btn-prev" data-prev="step1">Précédent</button>
            <button type="button" class="next-btn btn btn-next" data-next="step3">Suivant</button>
        </div>

        <div class="step" id="step3" style="display:none;">
            <h2>Informations interlocuteur</h2>
            <div class="form-names">
                <div class="input-group">
                    <!-- prenom interlocuteur -->
                    <input type="text" placeholder="Prénom" name="prenom-interlocuteur" class="input-case"
                        id="prenom-int">
                    <!-- Message d'erreur -->
                    <div id="prenom-int-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <!-- prenom interlocuteur -->
                    <input type="text" placeholder="Nom" name="nom-interlocuteur" class="input-case" id="nom-int">
                    <!-- Message d'erreur -->
                    <div id="nom-int-error" class="error-message" style="color: red; display: none;"></div>
                </div>
            </div>
            <div class="form-names">
                <div class="input-group">
                    <!-- email interlocuteur -->
                    <input type="email" placeholder="Adresse email" name='email-interlocuteur' class="input-case"
                        id="mail-int">
                    <!-- Message d'erreur -->
                    <div id="email-int-error" class="error-message" style="color: red; display: none;"></div>
                </div>
                <div class="input-group">
                    <!-- numero tel interlocuteur -->
                    <input type="text" placeholder="Numéro de téléphone" name="phone-interlocuteur" class="input-case"
                        id="tel-int">
                    <!-- Message d'erreur -->
                    <div id="tel-int-error" class="error-message" style="color: red; display: none;"></div>

                </div>
            </div>

            <button type="button" class="prev-btn btn btn-prev" data-prev="step2">Précédent</button>
            <button type="button" class="next-btn btn btn-next" data-next="step4">Suivant</button>
        </div>

        <div class="step" id="step4" style="display:none; width:550px;">
            <h2>Informations commercial</h2>
            <hr>
            <?php foreach ($gestionnaire as $p): ?>
                <div class="job_card commercial-item">
                    <div class="job_details">
                        <div class="img">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="text">
                            <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_personne'])):
                                  echo htmlspecialchars($p['id_personne']);
                              endif; ?>' class="block">
                                <h2>
                                    <?php
                                    if (array_key_exists('nom', $p)):
                                        echo htmlspecialchars($p['nom'] . ' ' . $p['prenom']);
                                    endif;
                                    ?>
                                </h2>
                            </a>
                            <span>
                                <?php
                                if (array_key_exists('mail', $p)):
                                    echo htmlspecialchars($p['mail']);
                                endif;
                                ?>
                            </span>

                        </div>
                    </div>

                    <div class="job_action">
                        <input type="checkbox" class="select-commercial large-checkbox" value="<?php if (isset($p['id_personne'])) {
                            echo $p['id_personne'];
                        } ?>">

                    </div>
                </div>
            <?php endforeach; ?>
            <div id="commercial-error" class="error-message" style="color: red; display: none;"></div>
            <button type="button" class="prev-btn btn btn-prev" data-prev="step3">Précédent</button>
            <button type="button" class="next-btn btn">Créer</button>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="Content/js/formStepsComp.js"></script>

</body>

</html>