<?php
require 'view_begin.php';
$menu = [['link' => '?controller=prestataire&action=missions', 'name' => 'Missions'],
    ['link' => '?controller=prestataire&action=clients', 'name' => 'Clients']];
require 'view_header.php';
?>
<div class="container">
    <div class="form-abs">
        <h1>Déclaration d'absence</h1>
        <form action="">
            <input type="text" placeholder="Prénom">
            <input type="text" placeholder="Nom">
            <input type="email" placeholder="Adresse email">
            <input type="date" placeholder="Date">
            <textarea name="motif" id="reason" placeholder="Motif de l'absence..."></textarea>

        </form>
        <div class="buttons">
            <button id="left-button">Enregistrer</button>
            <button id="right-button">Télécharger</button>
        </div>
    </div>
</div>
<?php
require 'view_end.php';
?>
