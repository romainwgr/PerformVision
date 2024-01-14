<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Mission</h1>
            <form action="">
                <h2>Informations mission</h2>
                <input type="text" placeholder="Nom de la mission" name='mission' class="input-case">
                <input type="text" placeholder="Société" id='sté' name='client' class="input-case">
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