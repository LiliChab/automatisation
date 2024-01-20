<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionnaire de notes</title>
  <style>
    .coefficients-header {
            background-color: #4CAF50;
            color: white;
        }

    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column; /* Change to column layout */
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    #uploadContainer, #etudiantsContainer { /* Separate container for the table */
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      margin-bottom: 20px; /* Add margin to separate the sections */
    }

    h1 {
      color: #333;
    }

    input[type="file"] {
      display: none;
    }

    label {
      display: inline-block;
      background-color: #4CAF50;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    button {
      background-color: #4CAF50;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    table {

      width: 100%;
      border-collapse: collapse;
      margin-top: 20px; /* Add margin to separate the sections */
      overflow-x: auto; /* Add horizontal scrollbar if needed */
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }
  </style>
</head>
<body>
  <div id="uploadContainer">
    <h1>Télécharger votre fichier de notes</h1>

    <!-- Formulaire d'envoi de fichier -->
    <form id="fileUploadForm" action="http://localhost:8080/index.php" method="post" enctype="multipart/form-data">
      <input type="file" id="fileInput" name="file" accept=".csv">
      <label for="fileInput">Choisir un fichier</label>
      <button type="submit" id="uploadButton" disabled>Envoyer</button>
    </form>

    <p id="uploadStatus"></p>
  </div>

  <div id="etudiantsContainer" style="display: none;">
  <h1>Liste des étudiants</h1>
  
  <div id="table-container">
    </div>

</div>


  <script>
    const fileInput = document.getElementById('fileInput');
    const uploadButton = document.getElementById('uploadButton');
    const uploadStatus = document.getElementById('uploadStatus');

    fileInput.addEventListener('change', function() {
      uploadButton.disabled = !fileInput.files.length;
    });

    document.getElementById('fileUploadForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      // Afficher un message de chargement
      uploadStatus.textContent = 'Envoi du fichier en cours...';

      await fetch('http://localhost:8080/index.php', {
        method: 'POST',
        body: formData,
      });

      // Message de succès
      uploadStatus.textContent = 'Fichier envoyé avec succès!';

      document.getElementById('etudiantsContainer').style.display = 'block';

      const etudiants = await getEtudiants();
      
      // Afficher le tableau dans la div avec l'id "etudiantsTable"
      document.getElementById('etudiantsContainer').innerHTML = afficherEtudiants(etudiants);
    });

    // Fonction pour récupérer les données du service C#
    async function getEtudiants() {
      apiUrl = "http://localhost:8080/index.php";
      try {
        const response = await fetch(apiUrl, {
          method: 'GET',
        });
        const etudiants = await response.json();
        return etudiants;
      } catch (error) {

        console.error('Erreur lors de la récupération des données:', error);
        return null;
      }
    }


    function afficherEtudiants(data) {
      var table = '<table border="1">';
        table += '<tr><th>Noms</th><th>Prénoms</th>';

        // Ajouter des colonnes pour chaque note
        for (var i = 0; i < data.coefficients.length; i++) {
            table += '<th>Note ' + (i + 1) + '</th>';
        }

        table += '</tr>';

        for (var i = 0; i < data.noms.length; i++) {
            table += '<tr>';
            table += '<td>' + data.noms[i] + '</td>';
            table += '<td>' + data.prenoms[i] + '</td>';

            // Ajouter chaque note dans une colonne distincte
            for (var j = 0; j < data.notesEtudiants[i].length; j++) {
                table += '<td>' + data.notesEtudiants[i][j] + '</td>';
            }

            table += '</tr>';
        }

        // Ajouter une ligne vide
        table += '<tr></tr>';

        // Ajouter les coefficients dans la dernière ligne, en dessous des notes
        // Ajouter la ligne pour les coefficients
        table += '<tr class="coefficients-header"><td colspan="2">Coefficients</td>';
        for (var i = 0; i < data.coefficients.length; i++) {
            table += '<td>' + data.coefficients[i] + '</td>';
        }
        table += '</tr>';

        table += '</table>';
        return table;
    }

    

    
</script>
</body>
</html>