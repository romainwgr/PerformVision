<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création Bon de Livraison</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    form {
        width: 80%;
        margin: auto;
    }
    label {
        display: block;
        margin-bottom: 8px;
    }
    input, select {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
    }
    button {
        background-color: #4F74B1;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #4F74B1;
        color: white;
    }
</style>
</head>

<body>
    <form id="bonDeLivraisonForm">
        <label for="mois">Mois :</label>
        <input type="text" id="mois" name="mois" required>
        <button type="button" onclick="submitForm()">Enregistrer</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Commentaire</th>
                <th>Nombre d'Heures</th>
                <!-- Ajoute ici d'autres entêtes de colonnes selon tes besoins -->
            </tr>
        </thead>
        <tbody id="joursTableBody">
            <!-- Les lignes pour les jours seront ajoutées dynamiquement ici -->
        </tbody>
    </table>

    <button type="button" onclick="getTableData()">Récupérer les données</button>

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
                        cell.textContent = jour + "/" + mois;
                    } else {
                        // Sinon, crée un input de type texte
                        var input = document.createElement('input');
                        input.type = 'text'; // Tu peux changer le type d'input selon tes besoins
                        input.name = 'inputJour' + jour + '_' + i; // Nom unique pour chaque input
                        cell.appendChild(input);
                    }
                }
            }
        }

        //--------------------------------

        function getTableData() {
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

    // Envoyer les données à un script PHP avec une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'traitement.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText); // Afficher la réponse du serveur dans la console
        }
    };
    xhr.send(JSON.stringify(tableData));
}}

    </script>
    
</body>
</html>
