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
            <p>Il y a plus de <span><?= count($person) ?></span> <?= $title ?></p>
        </div>

        <?php foreach ($person as $p): ?>
            <div class="job_card">
                <div class="job_details">
                    <div class="img">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="text">
                        <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_bdl'])):
                              echo $p['id_bdl'];
                          else:
                              echo $p['id'];
                          endif; ?>' class="block">
                            <h2><?php
                            if (array_key_exists('id_bdl', $p)):
                                echo $p['nom_mission'];
                            elseif (array_key_exists('nom', $p)):
                                echo $p['nom'] . ' ' . $p['prenom'];
                            elseif (array_key_exists('nom_client', $p) and array_key_exists('telephone_client', $p)):
                                echo $p['nom_client'];
                            elseif (array_key_exists('nom_composante', $p) and array_key_exists('nom_client', $p)):
                                echo $p['nom_composante'];
                            endif;
                            ?></h2>
                        </a>
                        <span><?php
                        if (array_key_exists('email', $p)):
                            echo 'Email: ' . $p['email'];
                        elseif (array_key_exists('autre_info', $p)):
                            echo 'Autre info: ' . $p['autre_info'];
                        elseif (array_key_exists('id_bdl', $p)):
                            echo $p['mois'];
                        elseif (array_key_exists('interne', $p)):
                            if ($p['interne']):
                                echo 'Interne';
                            else:
                                echo 'Indépendant';
                            endif;
                        elseif (array_key_exists('nom_client', $p) and !array_key_exists('telephone_client', $p)):
                            echo $p['nom_client'];
                        elseif (array_key_exists('nom_composante', $p) and !array_key_exists('nom_client', $p)):
                            echo $p['nom_composante'];
                        elseif (array_key_exists('telephone_client', $p)):
                            echo $p['telephone_client'];
                        endif;
                        ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>