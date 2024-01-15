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
} else {
    echo json_encode(['error' => 'Méthode non autorisée']);
}
