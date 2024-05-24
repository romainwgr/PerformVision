<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body">
        <div class="search_bar">
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input" placeholder="Search here...">
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
            <p>Il y a plus de <span><?= count($person) ?></span> entrées</p>
        </div>

        <div class="element-block">
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
                            <span>
                                <?php
                                if (array_key_exists('telephone', $p)):
                                    echo htmlspecialchars($p['telephone']);
                                endif;
                                ?>
                            </span>
                        </div>
                        <div class="job_action">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span>Voir BDL</span>
                            </a>
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
    </div>
</section>

<?php
require 'Views/view_end.php';
?>