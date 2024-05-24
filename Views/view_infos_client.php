<!-- Vue permettant de voir les informations de la société -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<!-- Vue pour afficher les interlocuteurs et les composantes -->
<section class="main">
    <div class="main-body">
        <div class="search_bar">
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input" placeholder="Rechercher...">
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="composante-container">
            <h2 class="marge">Informations sur la Société</h2>
            <a href="#caheaffiche" class="job-card-link">
                <div class="job_card">
                    <div class="job_details">
                        <div class="img">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="text">
                            <h2><?= $infos['nom_client'] ?></h2>
                            <span><?= $infos['telephone_client'] ?></span>
                        </div>
                    </div>
                    <div class="job_action">
                        <!-- Actions supplémentaires peuvent être ajoutées ici si nécessaire -->
                    </div>
                </div>
            </a>
        </div>

        <div class="infos-container">
            <div class="infos__colonne">
                <h2>Interlocuteurs</h2>
                <?php if (!empty($interlocuteurs) && is_array($interlocuteurs)): ?>
                    <table>
                        <tr>
                            <?php foreach ($interlocuteurs as $i): ?>
                                <td>
                                    <a href="?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $i['id_personne'] ?>"
                                        class="block">
                                        <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>Aucun interlocuteur trouvé.</p>
                <?php endif; ?>
                <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_interlocuteur_form&id-client=<?= $_GET['id'] ?>"
                    class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
            </div>
            <div class="infos__colonne">
                <h2>Composantes</h2>
                <table>
                    <tr>
                        <?php foreach ($composantes as $c): ?>
                            <td>
                                <a href='?controller=<?= $_GET['controller'] ?>&action=infos_composante&id=<?= $c['id_composante'] ?>'
                                    class="block">
                                    <h3><?= $c['nom_composante'] ?></h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </table>
                <?php if (!str_contains('commercial', $_GET['controller'])): ?>
                    <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_composante_form" class="ajout"><i
                            class="fa fa-solid fa-user-plus"></i>
                        &nbsp; Ajouter</a>
                <?php else: ?>
                    <a href="" class="ajout"></a>
                <?php endif; ?>
            </div>

            <div class="add-container" id="caheaffiche" style="display: none;">
                <div class="form-abs">
                    <span class="close-icon" onclick="closeForm()"> <!-- Ajout de l'icône de fermeture -->
                        <i class="fas fa-times"></i>
                    </span>
                    <form action="?controller=<?= $_GET['controller'] ?>&action=maj_infos_client&id=<?= $_GET['id'] ?>"
                        method="post" class="form">
                        <!-- Steps -->
                        <div class="form-step form-step-active">
                            <h2>Modifier les informations</h2>
                            <div class="input-group">
                                <input type="text" placeholder="<?= $infos['nom_client'] ?>" id="sté" name="prenom"
                                    class="input-case">
                            </div>
                            <div class="input-group">
                                <input type="text"
                                    placeholder="<?= isset($infos['telephone_client']) ? $infos['telephone_client'] : 'Numéro' ?>"
                                    id="sté" name="nom" class="input-case">
                            </div>
                        </div>
                        <div class="btns-group">
                            <input type="submit" value="Enregistrer" class="btn">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'view_end.php'; ?>