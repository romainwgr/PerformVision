<?php
require 'view_begin.php';
require 'view_header.php';
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
                <button type="button" class="button-primary" onclick="window.location='<?= $buttonLink ?>'">Ajouter</button>
            <?php endif; ?>
        </div>

        <div class="row">
            <p>Il y a plus de <span><?= count($dashboard) ?></span> entrées</p>
        </div>

        <?php foreach ($dashboard as $row): ?>
            <div class="job_card">
                <div class="job_details">
                    <div class="img">
                        <i class="fas fa-clipboard-list"></i> <!-- Icone exemple pour mission -->
                    </div>
                    <div class="text">
                        <a href='<?= $bdlLink ?><?php if (isset($row['id_prestataire'])):
                              echo '&id-prestataire=' . $row['id_prestataire'];
                          endif; ?>' class="block">

                            <h2><?php
                            if (isset($row['nom_mission'])):
                                echo $row['nom_mission'];
                            elseif (isset($row['nom']) && isset($row['prenom'])):
                                echo $row['prenom'] . ' ' . $row['nom'];
                            elseif (isset($row['nom_client']) && isset($row['telephone_client'])):
                                echo $row['nom_client'];
                            elseif (isset($row['nom_composante']) && isset($row['nom_client'])):
                                echo $row['nom_composante'];
                            endif;
                            ?></h2>
                        </a>
                        <span><?php if (isset($row['nom_client'])):
                            echo $row['nom_client'];
                        endif; ?></span>
                    </div>
                </div>
                <div class="job_salary">
                    <h3><?php if (isset($row['nom_composante'])):
                        echo $row['nom_composante'];
                    endif; ?></h3>
                    <span><?php if (isset($row['prenom'], $row['nom'])):
                        echo $row['prenom'] . ' ' . $row['nom'];
                    endif; ?></span>
                </div>
                <div class="job_action">
                    <a href="<?= $bdlLink ?><?php if (isset($row['id_prestataire'])):
                          echo '&id-prestataire=' . $row['id_prestataire'];
                      endif; ?>&id=<?= $row['id_mission'] ?>" class="action_link">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        <span>Consulter</span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>