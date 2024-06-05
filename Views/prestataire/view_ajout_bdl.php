<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <h1 class="text-center gauche">Ajouter un bon de livraison</h1>

        <div class="form-container">
            <form id="form-add-bdl" action="?controller=prestataire&action=addBdl" method="post" class="add-container form-abs">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="client">Client</label>
                        <select id="client" name="client" class="input-case" required>
                            <option value="">Sélectionner un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= htmlspecialchars($client['id_client']) ?>"><?= htmlspecialchars($client['nom_client']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Ajoutez les autres champs du formulaire ici -->
                </div>
                <div class="btns-group">
                    <button type="submit" class="btn">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
require 'view_end.php';
?>
