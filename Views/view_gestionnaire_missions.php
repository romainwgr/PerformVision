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
            <?php
            foreach ($_POST["header"] as $cle => $colonnes) {
                echo "<tr>";
                foreach ($colonnes as $valeurs){
                    echo "<th>$valeurs</th>";
                }

                echo "</tr>";
            }?>
        </thead>
        <tbody>
            <?php
            foreach ($_POST["tableau"] as $cle => $colonnes) {
                echo "<tr>";
                foreach ($colonnes as $valeurs){
                    echo "<td>$valeurs</td>";
                }
                echo '
                <td>
                    <div class="statut orange">L</div>
                    <div class="statut vert">M</div>
                    <div class="statut vert">M</div>
                    <div class="statut orange">J</div>
                    <div class="statut vert">V</div>
                </td>
                <td class="images">
                    <div>
                        <a href="#"><img src="images/icons8-visible-50.png" ></a>
                <p>Consulter</p>
                    </div>
                    <div>
                        <a href="#"><img src="images/icons8-install-58.png" ></a>
                        <p>Télécharger</p>
                    </div>
                </td>';
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<div class="creer_mission">
    <a href="Controllers_mission"> + Créer Mission</a>
</div>


<?php
require 'view_end.php';
?>
