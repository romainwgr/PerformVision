<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="bdl-container">
    <form id="bonDeLivraisonForm">
        <label id='label-mois' for="mois">Choissisez le mois (par son nombre):</label>
        <input class='input-bdl' type="text" id="mois" name="mois" required>
        <button type="button" onclick="submitForm()">Enregistrer</button>
    </form>

    <table class="bdl-table">
        <thead>
            <tr>
                <th>Date</th>
                <th><?= $bdl['type_bdl'] ?></th>
                <th>Commentaire</th>
                <!-- Ajoute ici d'autres entêtes de colonnes selon tes besoins -->
            </tr>
        </thead>
        <tbody id="joursTableBody">
            <!-- Les lignes pour les jours seront ajoutées dynamiquement ici -->
        </tbody>
    </table>

    <button type="button" onclick="getTableData()" id="button-get-data">Récupérer les données</button>
</div>
<script>
  function submitForm() {
    // Récupérer les valeurs du formulaire
    let mois = document.getElementById('mois').value;
    let nbjour;

    if (mois == 1 || mois == 3 || mois == 5 || mois == 7 || mois == 8 || mois == 10 || mois == 12) {
      nbjour = 31;
    } else if (mois == 2) {
      nbjour = 28;
    } else {
      nbjour = 30;
    }

    var moisForm = document.getElementById('bonDeLivraisonForm');
    moisForm.style.display = 'none';

    // Récupérer la table du corps
    var joursTableBody = document.getElementById('joursTableBody');
    joursTableBody.innerHTML = ''; // Réinitialiser le contenu actuel

    // Ajouter dynamiquement une ligne pour chaque jour
    for (let jour = 1; jour <= nbjour; jour++) {
      var newRow = joursTableBody.insertRow();

      for (let i = 0; i < 3; i++) {
        var cell = newRow.insertCell(i);

        // Si c'est la colonne "Date", affiche la valeur du jour
        if (i === 0) {
          cell.textContent = jour + '-' + mois + '-' + 2024;
        } else {
          // Sinon, crée un input de type texte
          var input = document.createElement('input');
          input.type = 'text'; // Tu peux changer le type d'input selon tes besoins
          input.name = 'inputJour' + jour + '_' + i; // Nom unique pour chaque input

          // Si c'est la colonne "Nombre d'Heures", définir la valeur par défaut à 1
          if (i === 1) {
            input.placeholder = '0';
          }

          cell.appendChild(input);
        }
      }
    }
  }

  //--------------------------------

  function getTableData() {
    var tableData = [];

    // Récupérer la table du corps
    var joursTableBody = document.getElementById('joursTableBody');
    var rows = joursTableBody.getElementsByTagName('tr');

    // Parcourir chaque ligne du tableau
    for (var i = 0; i < rows.length; i++) {
      var rowData = [];

      // Récupérer les cellules de la ligne
      var cells = rows[i].getElementsByTagName('td');

      // Parcourir chaque cellule de la ligne
      for (var j = 0; j < cells.length; j++) {
        // Vérifier si la cellule contient un input
        var input = cells[j].querySelector('input');

        if (input) {
          // Ajouter la valeur de l'input au tableau
          rowData.push(input.value);
        } else {
          // Si ce n'est pas un input, ajouter le texte de la cellule au tableau
          rowData.push(cells[j].textContent);
        }
      }

      // Ajouter les données de la ligne au tableau principal
      tableData.push(rowData);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '?controller=prestataire&action=completer_bdl&id=<?= $bdl['id_bdl'] ?>&type=<?= $bdl['type_bdl'] ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          console.log(xhr.responseText); // Afficher la réponse du serveur dans la console
          // Rediriger l'utilisateur vers une autre page ici
          window.location.href = '?controller=prestataire&action=afficher_bdl&id=<?= $bdl['id_bdl'] ?>';
        } else {
          console.error('Erreur lors de la requête AJAX');
        }
      }
    };
    xhr.send(JSON.stringify(tableData));
  }

</script>

