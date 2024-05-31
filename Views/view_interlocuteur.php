<!-- Vue de l'interlocuteur où il peut voir ses missions, y consulter ses bons de livraison et faire une demande -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<section class="main">
    <h1>Mes Prestataires</h1>

    <div class="main-body">
        <div class="search_bar">
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input" placeholder="Search here...">
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <a href="#caheaffiche" class="job-card-link">
                <button type="button" class="button-primary">Une demande
                    ?</button>
            </a>
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

                            if (isset($row['nom']) && isset($row['prenom'])):
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
                    <span><?php if (isset($row['telephone_client'])):
                        echo $row['telephone_client'];
                    endif; ?></span>
                </div>
                <div class="job_action">
                    <a href="<?= $bdlLink ?><?php if (isset($row['id_prestataire'])):
                          echo '&id-prestataire=' . $row['id_prestataire'];
                      endif; ?>" class="action_link">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        <span>Consulter</span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<div class="add-container" id="caheaffiche" style="display: none;">
    <div class="form-abs">
        <span class="close-icon" onclick="closeForm()"> <!-- Ajout de l'icône de fermeture -->
            <i class="fas fa-times"></i>
        </span>
        <form action="?controller=interlocuteur&action=envoyer_email" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Une demande ? C'est juste ici !</h2>
                <div class="input-group">
                    <label for="sté">Objet</label>
                    <input type="text" id="sté" name="objet" placeholder="Objet" class="input-case">
                </div>
                <div class="input-group">
                    <label for="sté">Message</label>
                    <textarea id="sté" name="message" placeholder="Votre message..." class="input-case"></textarea>
                </div>
            </div>
            <div class="btns-group">
                <input type="submit" value="Envoyer" class="btn">
            </div>
        </form>

    </div>
</div>

<?php
require 'view_end.php';
?>