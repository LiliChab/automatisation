<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Frontend</title>
</head>
<body>
  <h1>Sélectionner un fichier</h1>
  <select id="fileSelect">
  </select>
  <button onclick="selectFile()">Sélectionner</button>

  <script>

    // Envoie le nom du fichier sélectionné au service backend
    async function selectFile() {
      const selectedFileName = document.getElementById('fileSelect').value;

      await fetch('http://localhost:8080', {
        method: 'POST',
        body: JSON.stringify({ filename: selectedFileName }),
      });

      alert(`Fichier sélectionné : ${selectedFileName}`);
    }

    // Permet de générer la liste des fichiers transmise par le service backend
    async function fetchFileList() {
      const response = await fetch('http://localhost:8080');
      const fileList = await response.json();

      const fileSelect = document.getElementById('fileSelect');
      fileList.forEach(filename => {
        const option = document.createElement('option');
        option.value = filename;
        option.text = filename;
        fileSelect.add(option);
      });
    }

    // Effectue la fonction dès la génération de la page
    fetchFileList();
  </script>
</body>
</html>
