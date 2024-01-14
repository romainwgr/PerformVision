<div class="background-blur">
    <div class="popup">
        <div class="container-popup">
            <div class="img-popup">
                <img src="/Content/images/Questions-amico.svg" alt="">
            </div>
            <div class="form-popup">
                <h1 class="popup-title">Il se trouve que vous ayez plusieurs rôles !</h1>
                <form id="myForm" action="" method="get">
                    <div class="container-select-button">
                        <select id="roleSelect" class="form-select" name="controller">
                            <option value="" selected>Choisir mon rôle</option>
                            <?php foreach ($data['response']['roles'] as $role) : ?>
                                <option value="<?= $role ?>"><?= $role ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="button-primary">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  $(document).ready(function() {
    // Écouteur d'événement sur le changement de la sélection
    $("#roleSelect").change(function() {
      // Récupérer la valeur sélectionnée
      var selectedRole = $(this).val();

      // Mettre à jour l'attribut "action" du formulaire
      $("#myForm").attr("action", "?controller=" + selectedRole + "&action=default");
    });
  });
</script>