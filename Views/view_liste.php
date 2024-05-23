<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <div class="search_bar">
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input"
                    placeholder="Rechercher un emploi...">
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <?php if (!empty($buttonLink)): ?>
                <button type="button" class="button-primary"
                    onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
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
                            <?php
                            // Vérifie si le paramètre 'controller' est 'prestataire' et si le paramètre 'action' est 'liste_bdl'
                            if (isset($_GET['controller']) && $_GET['controller'] === 'prestataire' && isset($_GET['action']) && $_GET['action'] === 'liste_bdl'): ?>
                                <!-- Si la condition est vraie, crée un lien avec l'action 'afficher_bdl' et l'ID depuis le tableau $p -->
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

<?php
require 'view_end.php';
?>