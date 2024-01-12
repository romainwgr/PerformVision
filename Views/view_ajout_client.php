<?php
require 'view_begin.php';
$menu = [['link' => '?controller=commercial&action=missions', 'name' => 'Missions'],
    ['link' => '?controller=commercial&action=clients', 'name' => 'Clients'],
    ['link' => '?controller=commercial&action=prestataire', 'name' => 'Prestataires']];
require 'view_header.php';
?>
<div class="container">
    <div class="form-abs">
        <h1>Ajout Client</h1>
        <form action="">
            <h2>Informations personnelles</h2>
            <input type="text" placeholder="Prénom" id="f-name" class="input-case">
            <input type="text" placeholder="Nom" id="l-name" class="input-case">
            <input type="email" placeholder="Adresse email" id='mail-1' class="input-case">
            <h2>Informations professionnelles</h2>
            <input type="text" placeholder="Société" id='sté' class="input-case">
            <input type="text" placeholder="Composante" id='cpt' class="input-case">
        </form>
        <div class="buttons" id="create">
            <button>Créer client</button>
        </div>
    </div>
</div>
<?php
require 'view_end.php';
?>
