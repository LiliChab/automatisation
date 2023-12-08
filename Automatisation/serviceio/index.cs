// using System;
// using System.IO;
// using System.Net.Http;
// using System.Threading.Tasks;

// class Program
// {
//     static async Task Main()
//     {
//         // Chemin du fichier CSV
//         string csvFilePath = "csv/uploaded_file.csv";

//         // Vérifier si le fichier existe
//         if (File.Exists(csvFilePath))
//         {
//             // Lire toutes les lignes du fichier CSV
//             string[] lines = File.ReadAllLines(csvFilePath);

//             // Vérifier si le fichier contient des données
//             if (lines.Length > 0)
//             {
//                 // Créer un tableau pour stocker les lignes du fichier CSV
//                 string[] csvData = new string[lines.Length];

//                 // Afficher le contenu du fichier ligne par ligne
//                 for (int i = 0; i < lines.Length; i++)
//                 {
//                     // Diviser la ligne en colonnes en utilisant la virgule comme délimiteur
//                     string[] columns = lines[i].Split(',');

//                     // Stocker chaque colonne dans le tableau csvData
//                     csvData[i] = string.Join("\t", columns);
//                 }

//                 // Envoyer les données à la page PHP
//                 await SendDataToPHP(csvData);

//                 Console.WriteLine("Données envoyées avec succès à la page PHP.");
//             }
//             else
//             {
//                 Console.WriteLine("Le fichier CSV est vide.");
//             }
//         }
//         else
//         {
//             Console.WriteLine("Le fichier CSV n'existe pas.");
//         }

//         Console.ReadLine();
//     }

//     static async Task SendDataToPHP(string[] csvData)
//     {
//         // URL de votre fichier PHP sur le serveur local
//         string phpUrl = "http://localhost:8080/index.php";

//         // Convertir le tableau en une seule chaîne pour l'envoi
//         string postData = string.Join(",", csvData);

//         // Créer une requête HTTP POST
//         using (HttpClient client = new HttpClient())
//         {
//             var content = new StringContent("data=" + postData); // Modifier la clé 'data' en fonction de votre traitement dans le fichier PHP
//             var response = await client.PostAsync(phpUrl, content);
//             response.EnsureSuccessStatusCode();
//         }
//     }
// }
