<?php

// Autoriser toutes les origines (à ajuster en fonction de vos besoins)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Charger les dépendances de Slim
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Créer l'application Slim
$app = AppFactory::create();

// Coefficients
$coefficients = [4.75, 4.25, 2, 1.5, 3, 1.25, 1, 0.5, 1.75, 3.5, 1.25, 1.25, 2, 1.5, 0.5];

// Liste des notes de tous les étudiants
$toutes_les_notes = [
    [19, 20, 18.65625, 15, 17.60714286, 18.17647059, 15.8, 13.1, 19.4, 16.75, 14.5, 19.5, 14.10909091, 15.58333333, 19],
    [18.3, 19.9, 16.28125, 16, 16.30357143, 18.8125, 13.2, 15.69, 18.9, 17.875, 16.5, 18, 17.70909091, 16.33333333, 17]
];

// Définir une route pour l'API de données
$app->get('/api/data', function (Request $request, Response $response) use ($coefficients, $toutes_les_notes) {
    $data = [
        'coefficients' => $coefficients,
        'toutes_les_notes' => $toutes_les_notes,
    ];

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// Chemin où vous souhaitez enregistrer les fichiers uploadés
$uploadDirectory = '/var/www/html/fichiers/';

// Nouvelle route pour le traitement des fichiers
$app->map(['GET', 'POST'], '/api/upload', function (Request $request, Response $response) use ($uploadDirectory) {
    if ($request->getMethod() === 'POST') {
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
    } elseif ($request->getMethod() === 'GET') {
        // Appelez l'API du service C#
        $apiUrl = 'http://localhost:4000/api/etudiants';
        $etudiantsData = @file_get_contents($apiUrl);

        if ($etudiantsData !== false) {
            echo $etudiantsData;
        } else {
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
    } else {
        echo json_encode(['error' => 'Méthode non autorisée']);
    }
});

// Exécuter l'application Slim
$app->run();
