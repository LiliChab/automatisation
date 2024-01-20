using CsvHelper;
using System.Globalization;
using Microsoft.Extensions.Hosting;
using Newtonsoft.Json;
using Formatting = System.Xml.Formatting;


public class WatchFolderService : IHostedService
{
    // Liste pour stocker les noms et prénoms
        public static List<string> noms = new List<string>();
        public static List<string> prenoms = new List<string>();
       public static List<string> s1101 = new List<string>();
       public static List<string> s1102 = new List<string>();
       public static List<string> s1103 = new List<string>();
       public static List<string> s1104 = new List<string>();
       public static List<string> s1105a = new List<string>();
       public static List<string> s1105b = new List<string>();
        public static List<string> s1106 = new List<string>();
       public static  List<string> s1107 = new List<string>();
       public static List<string> s1108 = new List<string>();

        // Liste pour stocker les coefficients
       public static List<string> coefficients = new List<string>();
        // Liste pour stocker toutes les listes notesDeLaPosition
       public static List<List<string>> notesEtudiants = new List<List<string>>();

       
    
           // Initialiser le FileSystemWatcher
           
       

       public async Task StartAsync(CancellationToken cancellationToken)
       {
           await ExecuteAsync(cancellationToken);
       }

       public Task StopAsync(CancellationToken cancellationToken)
       {
           // Arrêtez votre service ici
           return Task.CompletedTask;
       }

       private async Task ExecuteAsync(CancellationToken stoppingToken)
       {
           try
           {
               string folderPath = "fichiers";
               FileSystemWatcher fileSystemWatcher = new FileSystemWatcher(folderPath);
               fileSystemWatcher.Created += OnFileCreated;
               fileSystemWatcher.EnableRaisingEvents = true;
           }
           catch (OperationCanceledException)
           {
               // Cela se produit lorsque stoppingToken est annulé, ce qui est attendu lors de l'arrêt du service.
           }
       }
    

    private void OnFileCreated(object sender, FileSystemEventArgs e)
    {
        // Appeler la méthode de traitement du fichier
        ProcessCsvFile(e.FullPath);
    }
    
    private void ProcessCsvFile(string filePath)
    {

        // Configuration de CsvHelper pour utiliser le point-virgule comme délimiteur
        var csvConfig = new CsvHelper.Configuration.CsvConfiguration(CultureInfo.InvariantCulture)
        {
            Delimiter = ";"
        };

        // Lecture du fichier CSV
        using (var reader = new StreamReader(filePath)) //changer par filePath quand la connexion c# web se fera
        using (var csv = new CsvReader(reader, csvConfig))
        {
            // Lecture des enregistrements
            var records = csv.GetRecords<dynamic>();
            
            // Index du record que vous souhaitez récupérer
            int targetRecordIndex = 26; // L'index commence à 0, donc le 27e record a l'index 26

            int currentIndex = 0;
            
            // Parcours des enregistrements et extraction des noms et prénoms
            foreach (var record in records)
            {
                // Vérification pour s'assurer que le champ "Nom" n'est pas vide
                if (!string.IsNullOrWhiteSpace(record.Nom))
                {
                    s1101.Add(record.S1101);
                    s1102.Add(record.S1102);
                    s1103.Add(record.S1103);
                    s1104.Add(record.S1104);
                    s1105a.Add(record.S1105A);
                    s1105b.Add(record.S1105B);
                    s1106.Add(record.S1106);
                    s1107.Add(record.S1107);
                    s1108.Add(record.S1108);
                    noms.Add(record.Nom);
                    prenoms.Add(record.Prenom);
                }
                
                // Vérifier si nous avons atteint l'index souhaité
                if (currentIndex == targetRecordIndex)
                {
                    // Ajouter le traitement supplémentaire si nécessaire
                    foreach (var property in record)
                    {
                        if (property.Key.StartsWith("S110"))
                        {
                            coefficients.Add(property.Value);
                        }
                    }
                    
                    // Sortir de la boucle une fois que le record souhaité est trouvé
                    break;
                }

                currentIndex++;
                
            }
        }

        // Création d'une liste pour stocker toutes les listes s110*
        List<List<string>> toutesLesListes = new List<List<string>>
            { s1101, s1102, s1103, s1104, s1105a, s1105b, s1106, s1107, s1108 };


        // Boucle pour parcourir chaque position dans les listes s110*
        for (int position = 0; position < toutesLesListes[0].Count; position++)
        {
            // Liste pour stocker les notes de la position actuelle
            List<string> notesDeLaPosition = new List<string>();

            // Boucle pour parcourir chaque liste s110* et ajouter la note à la liste
            foreach (var liste in toutesLesListes)
            {
                notesDeLaPosition.Add(liste.Count > position ? liste[position] : "0");
            }

            // Ajouter la liste notesDeLaPosition à la liste principale
            notesEtudiants.Add(notesDeLaPosition);
        }
        
    }
}
