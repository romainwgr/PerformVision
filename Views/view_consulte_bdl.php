<!-- Vue permettant de consulter un bdl et si il est interlocuteur client de le contester ou valider -->

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
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Présence </th>
                    <th>Nombre d'heures </th>
                    <th>Nombre de demi-journées </th>
                    <th>Commentaire </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activites as $activite): ?>
                    <tr>
                        <td><?php echo isset($activite["date_bdl"]) ? $activite["date_bdl"] : ''; ?></td>
                        <td><?php
                        if (isset($activite['journee'])) {
                            echo $activite['journee'] ? 'Présent' : 'Absent';
                        }
                        ?></td>
                        <td><?php echo isset($activite['nb_heure']) ? $activite['nb_heure'] : ''; ?></td>
                        <td><?php echo isset($activite['nb_demi_journee']) ? $activite['nb_demi_journee'] : ''; ?></td>
                        <td><?= $activite["commentaire"] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
require 'view_end.php';
?>