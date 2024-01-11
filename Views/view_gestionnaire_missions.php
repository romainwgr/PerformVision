<?php
require 'view_begin.php';
?>

<header>
    <h1>Perform Vision</h1>
    <nav class="nav-links">
        <a href="Controllers_manager">Missions</a>
        <a href="Controllers_Controllers?action=clients">Clients</a>
        <a href="#">Prestataire</a>
        <a href="#">Commerciaux</a>
    </nav>
</header>

<div class="missions">
    <h1>Missions</h1>
</div>

<div class="dashboard-container">

    <table id="table-container" >
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
            <tr>
                <td>Nom de mission</td>
                <td>01/01/2024</td>
                <td>David Dupont</td>

                <td>
                    <div class="statut vert">L</div>
                    <div class="statut orange">M</div>
                    <div class="statut vert">M</div>
                    <div class="statut orange">J</div>
                    <div class="statut vert">V</div>
                </td>
                <td class="images">
                    <div class="">
                        <a href="#"><img src="images/icons8-visible-50.png" ></a>
                        <p>Consulter</p>
                    </div>

                    <div >
                        <a href="#"><img src="images/icons8-install-58.png" ></a>
                        <p>Télécharger</p>
                    </div>
                </td>

            </tr>
            <tr>
                <td>Nom de mission</td>
                <td>02/01/2024</td>
                <td>Jean Dupont</td>
                <td>
                    <div class="statut orange">L</div>
                    <div class="statut vert">M</div>
                    <div class="statut vert">M</div>
                    <div class="statut orange">J</div>
                    <div class="statut vert">V</div>
                </td>
                <td class="images" >
                    <div>
                        <a href="#"><img src="images/icons8-visible-50.png" ></a>
                        <p>Consulter</p>
                    </div>

                    <div>
                        <a href="#"><img src="images/icons8-install-58.png" ></a>
                        <p>Télécharger</p>
                    </div>
                </td>
            </tr>
            <!-- Ajoutez d'autres lignes -->
        </tbody>
    </table>
</div>


<?php
require 'view_end.php';
?>
