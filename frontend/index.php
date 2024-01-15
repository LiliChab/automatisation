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
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    #uploadContainer {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
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

      // Afficher un message de succès
      uploadStatus.textContent = 'Fichier envoyé avec succès!';
    });
  </script>
</body>
</html>