<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Interlocuteur Client</h1>
            <form action="">
                <h2>Informations personnelles</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom" class="input-case">
                    <input type="text" placeholder="Nom" name="nom" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email' id='mail-1' class="input-case">
                <h2>Informations professionnelles</h2>
                <input type="text" placeholder="Société" name='client' id='sté' class="input-case">
                <input type="text" placeholder="Composante" name='composante' id='cpt' class="input-case">
                <div class="buttons" id="create">
                    <button type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?>