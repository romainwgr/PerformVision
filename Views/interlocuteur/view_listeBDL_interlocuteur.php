<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>

<section class="main">
    <div class="main-body">
        <div class="search-box">
            <form action="<?= $rechercheLink ?>" method="post" class="search_form">
                <input name="recherche" type="text" placeholder="Rechercher une <?= strtolower($title) ?>..." value="<?php if (isset($val_rech)) {
                      echo htmlspecialchars($val_rech);
                  } ?>">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="cancel-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="search-data">
                </div>
            </form>
        </div>
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
                        <?php
                        // Vérifie si le paramètre 'controller' est 'prestataire' et si le paramètre 'action' est 'liste_bdl'
                        if (isset($_GET['controller']) && $_GET['controller'] === 'interlocuteur' && isset($_GET['action']) && $_GET['action'] === 'liste_bdl'): ?>
                            <!-- Si la condition est vraie, crée un lien avec l'action 'afficher_bdl' et l'ID depuis le tableau $p -->
                            <a href="?controller=interlocuteur&action=afficher_bdl&id_bdl=<?= htmlspecialchars($p['id_bdl'] ?? $p['id']) ?>"
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
                                ?></span>
                            </a>
                        <?php else: ?>
                            <!-- Si la condition est fausse, crée un lien avec l'ID depuis le tableau $p et le lien prédéfini $cardLink -->
                            <a href='<?= $cardLink ?>&id=<?php
                              // Vérifie si 'id_bdl' est défini dans le tableau $p
                              if (isset($p['id_bdl'])):
                                  echo htmlspecialchars($p['id_bdl']);
                                  // Sinon, vérifie si 'id' est défini dans le tableau $p
                              elseif (isset($p['id'])):
                                  echo htmlspecialchars($p['id']);
                              endif; ?>' class="block">
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
                                ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="job_action">
                    <a
                        href='?controller=interlocuteur&action=afficher_bdl&id_bdl=<?= htmlspecialchars($p['id_bdl'] ?? $p['id']) ?>'>
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        <span>Voir PDF</span>
                    </a>
                </div>
                <div class="button-container">
                    <?php if ($p['signature_interlocuteur']): ?>
                        <p>BDL Validé</p>
                    <?php else: ?>
                        <form id="form-validate-bdl" action="?controller=interlocuteur&action=validerbdl" method="post"
                            onsubmit="return confirmSubmit()">
                            <input type="hidden" name="id_bdl" value="<?= htmlspecialchars($p["id_bdl"]) ?>">
                            <div>
                                <button type="submit" class="button-primary">Valider le BDL</button><br>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="button-container">
                    <button type="button" class="button-primary" onclick="openCommentPopup(<?= htmlspecialchars($p['id_bdl']) ?>)">Ajouter un commentaire</button>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (
        ((strstr($_GET['controller'], 'gestionnaire') || strstr($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
        || ((strstr($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))
    ): ?>
    <?php endif; ?>
</section>

<!-- Fenêtre pop-up pour ajouter un commentaire -->
<div id="commentPopup" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; box-shadow:0px 0px 10px rgba(0,0,0,0.5); z-index:1000;">
    <form id="commentForm" action="?controller=interlocuteur&action=ajouter_commentaire" method="post">
        <input type="hidden" id="popup-id_bdl" name="id_bdl" value="">
        <label for="commentaire">Commentaire:</label><br>
        <textarea id="commentaire" name="commentaire" rows="4" cols="50"></textarea><br><br>
        <button type="button" onclick="submitCommentForm()">Ajouter</button>
        <button type="button" onclick="closeCommentPopup()">Fermer</button>
    </form>
</div>

<script>
function openCommentPopup(id_bdl) {
    console.log("openCommentPopup called with id_bdl:", id_bdl);
    var popup = document.getElementById('commentPopup');
    var bdlIdField = document.getElementById('popup-id_bdl');
    bdlIdField.value = id_bdl;
    popup.style.display = 'block';
}

function closeCommentPopup() {
    var popup = document.getElementById('commentPopup');
    popup.style.display = 'none';
}

function submitCommentForm() {
    var form = document.getElementById('commentForm');
    form.submit();
}

<?php if (isset($bdl) && count($bdl) == 0): ?>
    document.getElementById('errorMessage').innerHTML = 'Aucun BDL trouvé pour cet ID.';
    document.getElementById('errorMessage').style.display = 'block';
<?php endif; ?>
</script>

<?php require 'Views/view_end.php'; ?>
