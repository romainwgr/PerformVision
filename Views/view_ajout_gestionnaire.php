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










<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<section class="main">
    <div class="main-body">
        <div class="search_bar">
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input" placeholder="Search job here...">
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <?php if (!empty($buttonLink)): ?>
                <button type="button" class="button-primary" onclick="window.location='<?= $buttonLink ?>'">Ajouter</button>
            <?php endif; ?>
        </div>

        <div class="row">
            <p>Il y a plus de <span><?= isset($person) && is_array($person) ? count($person) : 0 ?></span>
                <?= htmlspecialchars($title) ?></p>
        </div>
        <div id="errorMessage" style="display: none;"></div>

        <?php if (is_string($person)): ?>
            <p class=""><?= htmlspecialchars($person); ?></p>
        <?php elseif (isset($person) && !empty($person)): ?>
            <?php foreach ($person as $p): ?>
                <div class="job_card">
                    <div class="job_details">
                        <div class="img">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="text">
                            <a href="?controller=prestataire&action=afficher_bdl&id_bdl=<?= htmlspecialchars($p['id_bdl'] ?? $p['id']) ?>"
                                class="block">
                                <h2><?php
                                if (array_key_exists('nom', $p)):
                                    echo htmlspecialchars($p['nom'] . ' ' . $p['prenom']);
                                elseif (array_key_exists('nom_client', $p) && array_key_exists('telephone_client', $p)):
                                    echo htmlspecialchars($p['nom_client']);
                                elseif (array_key_exists('nom_composante', $p) && array_key_exists('nom_client', $p)):
                                    echo htmlspecialchars($p['nom_composante']);
                                endif;
                                ?></h2>
                                <span><?php
                                if (array_key_exists('mois', $p)):
                                    echo htmlspecialchars($p['mois']);
                                elseif (array_key_exists('interne', $p)):
                                    echo $p['interne'] ? 'Interne' : 'Indépendant';
                                elseif (array_key_exists('nom_client', $p) && !array_key_exists('telephone_client', $p)):
                                    echo htmlspecialchars($p['nom_client']);
                                elseif (array_key_exists('nom_composante', $p) && !array_key_exists('nom_client', $p)):
                                    echo htmlspecialchars($p['nom_composante']);
                                elseif (array_key_exists('telephone_client', $p)):
                                    echo htmlspecialchars($p['telephone_client']);
                                endif;
                                ?></php>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (
            ((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
            || ((str_contains($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))
        ): ?>
        <?php endif; ?>
    </div>
</section>

<script>
    <?php if (count($bdl) == 0): ?>
        document.getElementById('errorMessage').innerHTML = 'Aucun BDL trouvé pour cet ID.';
        document.getElementById('errorMessage').style.display = 'block';
    <?php endif; ?>
</script>