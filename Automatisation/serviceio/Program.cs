
              using System;
using System.IO;
using System.Net;
using System.Threading.Tasks;

namespace ServiceIO
{
    class Program
    {
        static async Task Main(string[] args)
        {
            // Code pour effectuer des tâches ou attendre des requêtes
            // Vous pouvez ajouter votre logique ici
            await WaitForHttpPost();
        }

        static async Task WaitForHttpPost()
        {
            HttpListener listener = new HttpListener();
            listener.Prefixes.Add("http://localhost:8080/");
            listener.Start();

            Console.WriteLine("En attente de la requête HTTP POST...");

            while (true)
            {
                HttpListenerContext context = await listener.GetContextAsync();
                HttpListenerRequest request = context.Request;

                // Lire le nom du fichier à partir du corps de la requête
                using (StreamReader reader = new StreamReader(request.InputStream))
                {
                    string fileName = await reader.ReadToEndAsync();
                    Console.WriteLine($"Nom de fichier reçu : {fileName}");

                    // Vous pouvez maintenant traiter le fichier ou effectuer toute autre logique nécessaire
                    // Exemple : lecture du fichier CSV
                    string csvFilePath = $"../backend/fichiers/{fileName}";

                    if (File.Exists(csvFilePath))
                    {
                        // Lire et traiter le fichier ici
                        Console.WriteLine($"Lecture du fichier : {csvFilePath}");
                    }
                    else
                    {
                        Console.WriteLine($"Le fichier n'existe pas : {csvFilePath}");
                    }
                }

                // Vous pouvez également ajouter une pause ou d'autres conditions pour contrôler le comportement
            }

            // Arrêter le listener
            // listener.Stop();
        }
    }
}
