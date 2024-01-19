<?php
// Autoriser toutes les origines (à ajuster en fonction de vos besoins)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


// Chemin où vous souhaitez enregistrer les fichiers uploadés
$uploadDirectory = '/var/www/html/fichiers/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadedFile = $_FILES['file'];

    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $targetPath = $uploadDirectory . basename($uploadedFile['name']);

        // Déplacer le fichier uploadé vers le dossier spécifié
        if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            echo json_encode(['message' => 'Fichier uploadé avec succès']);
        } else {
            echo json_encode(['error' => 'Erreur lors de l\'enregistrement du fichier']);
        }
    } else {
        echo json_encode(['error' => 'Erreur lors de l\'envoi du fichier']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Appelez l'API du service C#
    $apiUrl = 'http://localhost:4000/api/etudiants';
    $etudiantsData = @file_get_contents($apiUrl);

    if ($etudiantsData !== false) {
        echo $etudiantsData;
    }else{
        // si la connexion ne marche pas avec le c#, envoie des données d'exemple
        echo json_encode([
            [
                'Nom' => 'Frido',
                'Prenom' => 'George',
                'Coefficients' => [
                    'Matiere1' => 2.0,
                    'Matiere2' => 1.5,
                    'Matiere3' => 1.0,
                ],
                'NotesEtudiants' => [
                    'Matiere1' => 18,
                    'Matiere2' => 15,
                    'Matiere3' => 20,
                ],
            ],
            [
                'Nom' => 'Carré',
                'Prenom' => 'Léa',
                'Coefficients' => [
                    'Matiere1' => 2.0,
                    'Matiere2' => 1.5,
                    'Matiere3' => 1.0,
                ],
                'NotesEtudiants' => [
                    'Matiere1' => 16,
                    'Matiere2' => 14,
                    'Matiere3' => 19,
                ],
            ]
        ]);
    }
}else {
    echo json_encode(['error' => 'Méthode non autorisée']);
}

?>
