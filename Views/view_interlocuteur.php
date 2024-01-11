<?php
require 'view_begin.php';
?>
<header>
    <div class="navbar">
        <div class="logo">Perform Vision</div>
        <nav>
            <ul>
                <li><a href="#"></a></li>
                <li><a href="#"><img src="Content/images/profile-simple-svgrepo-com.svg"></a></li>
                <li><a href="#">Nom <br> Prenom</a></li>

                <li><a href="#"><img src="Content/images/door.svg"></a></li>
            </ul>
        </nav>
    </div>
</header>

<div class='main-contrainer'>

    <div class="dashboard-container">
        <h1>Mes Prestataires</h1>
        <div class='dashboard__table'>
            <table>
                <thead>
                    <tr>
                        <th>Nom projet/société</th>
                        <th>Date</th>
                        <th>Préstataire assigné</th>
                        <th>Statut</th>
                        <th>Bon de livraison</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dashboard as $row): ?>
                        <tr>
                            <td><?= $row['nom_mission'] ?></td>
                            <td><?= $row['date_debut'] ?></td>
                            <td><?= $row['prenom'] . ' ' . $row['nom'] ?></td>

                            <td>
                                <div class="statut vert">L</div>
                                <div class="statut orange">M</div>
                                <div class="statut vert">M</div>
                                <div class="statut orange">J</div>
                                <div class="statut vert">V</div>
                            </td>

                            <td style="display: flex; justify-content: space-around;">
                                <div style="text-align: center;">
                                    <a href="#"><img src="Content/images/icons8-visible-50.png"
                                                     style="padding-bottom: 5px;"></a>
                                    <p>Consulter</p>
                                </div>

                                <div style="text-align: center;">
                                    <a href="#"><img src="/Content/images/icons8-install-58.png"
                                                     style="padding-bottom: 5px;"></a>
                                    <p>Télécharger</p>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class='form-email'>
        <h1 class="demande"> Une demande ? C'est juste ici !</h1>
        <form action="?controller=interlocuteur&action=envoyer_email" method="post">
            <input type="text" id="objet" name="objet" placeholder="Objet">

            <textarea id="message" name="message" placeholder="Votre message..."></textarea>

            <button type="submit" id="bouton-envoyer">Envoyer</button>
        </form>
    </div>
</div>

<?php
require 'view_end.php';
?>
