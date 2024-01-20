<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionnaire de notes</title>
  <style>
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
      
      // Appelez la fonction pour afficher les étudiants
      afficherEtudiants(etudiants); 
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

    function afficherEtudiants(etudiants) {
      const table = document.createElement('table');
      const headerRow = table.insertRow();
      const nomHeaderCell = document.createElement('th');
      const prenomHeaderCell = document.createElement('th');
      nomHeaderCell.textContent = 'Nom';
      headerRow.appendChild(nomHeaderCell);
      prenomHeaderCell.textContent = 'Prénom';
      headerRow.appendChild(prenomHeaderCell);
      const subjects = Object.keys(etudiants[0].Coefficients);

      subjects.forEach(subject => {
        const noteHeaderCell = document.createElement('th');
        noteHeaderCell.textContent = `${subject} (Note)`;
        headerRow.appendChild(noteHeaderCell);

        const coefficientHeaderCell = document.createElement('th');
        coefficientHeaderCell.textContent = `${subject} (Coefficient)`;
        headerRow.appendChild(coefficientHeaderCell);
      });


      etudiants.forEach(student => {
        const row = table.insertRow();
        row.insertCell().textContent = student.Nom;
        row.insertCell().textContent = student.Prenom;

        subjects.forEach(subject => {
          row.insertCell().textContent = student.NotesEtudiants[subject];
          row.insertCell().textContent = student.Coefficients[subject];
        });
      });

      const tableContainer = document.getElementById('table-container');
      tableContainer.innerHTML = '';
      tableContainer.appendChild(table);
    }
    // Fonction pour afficher les moyennes


</script>
<?php
// Utilisation de PHP pour récupérer les données de l'API et les afficher

// Effectuez une requête GET à l'API
$api_url = 'http://backend:80/api/moyennes';
$response = file_get_contents($api_url);

// Vérifiez si la requête a réussi
if ($response === FALSE) {
    echo 'Erreur lors de la récupération des données de l\'API.';
} else {
   // Convertissez la réponse JSON en tableau associatif
   $data = json_decode($response, true);

    // Manipulez les données récupérées ici
    echo '<h2>Moyennes:</h2>';
    echo '<pre>' . print_r($data['moyennes'], true) . '</pre>';
}
?>

</body>
</html>
