<?php
// Autoriser l'accès depuis n'importe quelle origine
header("Access-Control-Allow-Origin: *");

// Autoriser les méthodes HTTP spécifiées
header("Access-Control-Allow-Methods: GET, POST");

// Chemin vers le dossier contenant les fichiers de notes
$dossier = './fichiers/';

// Récupére la liste des fichiers dans le dossier
$fileList = scandir($dossier);


// Filtre les fichiers pour exclure les entrées '.' et '..'
$fileList = array_diff($fileList, ['.', '..']);
// Filtre les fichiers commençant par '.'
$fileList = array_values(array_filter($fileList, function($fichier) {
    return $fichier[0] !== '.';
}));


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Transmission de la liste de fichiers lors d'un GET
    echo json_encode($fileList);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {

    // Réception du nom du fichier sélectionné lors d'un POST
    $selectedFileName = $_POST['filename'];

    // Appel à une fonction qui communique avec le service C#
    declencherLectureFichier($selectedFileName);

    // à enlever après avoir fait lien avec c#
    echo json_encode(['message' => 'Fichier sélectionné traité avec succès.']); 
}



// Fonction pour déclencher la lecture du fichier par le service IO
function declencherLectureFichier($filename) {
    
    $urlServiceIO = 'http://serviceio/'; 
    $data = ['filename' => $filename];

    $ch = curl_init($urlServiceIO);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Exécution de la requête
    $response = curl_exec($ch);

    // Gestion des erreurs si nécessaire
    if (curl_errno($ch)) {
        // Gérer l'erreur ici
        echo 'Erreur cURL : ' . curl_error($ch);
    }

    curl_close($ch);
}