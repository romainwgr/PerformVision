<!-- Vue permettant de voir les informations d'une composante -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
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
            <h2 class="marge">Informations composante</h2>
            <a href="#caheaffiche" class="job-card-link">
                <div class="job_card">
                    <div class="job_details">
                        <div class="img">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="text">
                            <h2><?= $infos['nom_composante'] ?></h2>
                            <span><?= $infos['nom_client'] ?></span>
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
                <table>
                    <tr>
                        <?php foreach ($interlocuteurs as $i): ?>
                            <td>
                                <a href="?controller=<?= $_SESSION['role'] ?>&action=infos_personne&id=<?= $i['id_personne'] ?>"
                                    class="block"></a>
                                <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </table>
                <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_interlocuteur_form&id=<?= $_GET['id'] ?>"
                    class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>

            </div>
            <div class="infos__colonne">
                <h2>Commerciaux</h2>
                <table>
                    <tr>
                        <?php foreach ($commerciaux as $c): ?>
                            <td>
                                <a href='?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $c['id_personne'] ?>'
                                    class="block">
                                    <h3><?= $c['nom'] . ' ' . $c['prenom'] ?></h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </table>
                <?php if (!str_contains($_GET['controller'], 'commercial')): ?>
                    <a href="<?= $cardLink ?>&action=ajout_commercial_form&id=<?= $_GET['id'] ?>" class="ajout"><i
                            class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php else: ?>
                    <a class="ajout"> &nbsp;</a>
                <?php endif; ?>


                <div class="infos__colonne">
                    <h2>Prestataires</h2>
                    <table>
                        <tr>
                            <?php foreach ($prestataires as $p): ?>
                                <td>
                                    <a href="?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $p['id_personne'] ?>"
                                        class="block">
                                        <h3><?= $p['nom'] . ' ' . $p['prenom'] ?></h3>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                    <?php if (!str_contains($_GET['controller'], 'commercial')): ?>
                        <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_prestataire_form&id=<?= $_GET['id'] ?>"
                            class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                    <?php else: ?>
                        <a class="ajout"> &nbsp;</a>
                    <?php endif; ?>


                    <div class="infos__colonne">
                        <h2>Bons de livraison</h2>
                        <table>
                            <tr>
                                <?php if (isset($b['id_bdl'], $b['nom'], $b['prenom'], $b['mois'])): ?>
                                    <?php foreach ($bdl as $b): ?>
                                        <td>
                                            <a href="?controller=<?= $_GET['controller'] ?>&action=consulter_bdl&id=<?= $b['id_bdl'] ?>"
                                                class="block">
                                                <h3><?= htmlspecialchars($b['nom']) . ' ' . htmlspecialchars($b['prenom']) . '<br>' . htmlspecialchars($b['mois']) ?>
                                                </h3>
                                            </a>

                                        </td>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <td>
                                        <p>Aucun bons de livraison pour ce composante</p>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        </table>
                        <?php if (!str_contains($_GET['controller'], 'commercial')): ?>
                            <a href="<?= $cardLink ?>&action=ajout_bdl&id=<?= $_GET['id'] ?>" class="ajout"><i
                                    class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                        <?php else: ?>
                            <a class="ajout"> &nbsp;</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="add-container" id="caheaffiche" style="display: none;">
            <div class="form-abs">
                <span class="close-icon" onclick="closeForm()"> <!-- Ajout de l'icône de fermeture -->
                    <i class="fas fa-times"></i>
                </span>
                <h1 class="text-center">Modifier la composante</h1>
                <form
                    action="?controller=<?= $_GET['controller'] ?>&action=<?php echo isset($_GET['id']) ? 'maj_infos_composante&id=' . $_GET['id'] : 'ajout_composante'; ?>"
                    method="post" class="form">

                    <!-- Steps -->
                    <div class="form-step form-step-active">
                        <div class="input-group">
                            <label for="composante">Nom de la composante</label>
                            <input type="text" placeholder="<?= $infos['nom_composante'] ?>" name='composante'
                                id='composante' class="input-case">
                        </div>
                        <h4>Adresse</h4>
                        <div class="form-address">
                            <input type="number" placeholder="<?= $infos['numero'] ?>" name="numero-voie"
                                class="input-case form-num-voie">
                            <input type="text" placeholder="<?= $infos['libelle'] ?>" name="type-voie"
                                class="input-case form-type-voie">
                            <input type="text" placeholder="<?= $infos['nom_voie'] ?>" name="nom-voie"
                                class="input-case form-nom-voie">
                        </div>
                        <div class="form-address">
                            <input type="number" placeholder="<?= $infos['cp'] ?>" name="cp" class="input-case form-cp">
                            <input type="text" placeholder="<?= $infos['ville'] ?>" name="ville"
                                class="input-case form-ville">
                        </div>
                        <div class="btns-group">
                            <input type="submit" value="Enregistrer" class="btn">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
require 'view_end.php';
?>