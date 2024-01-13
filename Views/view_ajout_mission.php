<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Mission</h1>
            <form action="">
                <h2>Informations interlocuteur</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" id="f-name" class="input-case">
                    <input type="text" placeholder="Nom" id="l-name" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" id='mail-1' class="input-case">
                <h2>Informations commercial</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" id="f-name" class="input-case">
                    <input type="text" placeholder="Nom" id="l-name" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" id='mail-1' class="input-case">
                <h2>Informations mission</h2>
                <input type="text" placeholder="Nom de la mission" id='sté' class="input-case">
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