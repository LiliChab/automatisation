using System;
using System.Collections.Generic;
using System.Globalization;
using System.IO;
using CsvHelper;

class Program
{
    static void Main(string[] args)
    {
        WatchFolder();
        CreateHostBuilder(args).Build().Run();
    }
    public static IHostBuilder CreateHostBuilder(string[] args) =>
        Host.CreateDefaultBuilder(args)
            .ConfigureWebHostDefaults(webBuilder =>
            {
                webBuilder.UseStartup<Startup>();
            });

    static void WatchFolder()
    {
        // Chemin du dossier à surveiller
        string folderPath = @"./fichiers";

        // Créer une instance de FileSystemWatcher
        FileSystemWatcher watcher = new FileSystemWatcher(folderPath);

        // Activer les notifications pour les changements de création de fichiers
        watcher.NotifyFilter = NotifyFilters.FileName | NotifyFilters.LastWrite;

        // Événement déclenché lorsqu'un fichier est créé ou modifié
        watcher.Created += OnFileCreated;

        // Démarrer la surveillance
        watcher.EnableRaisingEvents = true;

        Console.WriteLine("Service C# en attente d'un fichier. Le programme continuera à surveiller le dossier.");
        // Laisser le programme en attente indéfiniment
        while (true)
        {
            // Ajouter une petite pause pour éviter une utilisation excessive du processeur
            System.Threading.Thread.Sleep(600000);
        }
        
    }

    private static void OnFileCreated(object sender, FileSystemEventArgs e)
    {
        // Appeler la méthode de traitement du fichier
        ProcessCsvFile(e.FullPath);
    }

    private static void ProcessCsvFile(string filePath)
    {
        // Chemin du fichier CSV
        string csvFilePath = "DUT_Informatique_en_Annee_Speciale_semestre_1-2023-10-23.csv";

        // Liste pour stocker les noms et prénoms
        List<string> noms = new List<string>();
        List<string> prenoms = new List<string>();
        List<double> s1101 = new List<double>();
        List<double> s1102 = new List<double>();
        List<double> s1103 = new List<double>();
        List<double> s1104 = new List<double>();
        List<double> s1105a = new List<double>();
        List<double> s1105b = new List<double>();
        List<double> s1106 = new List<double>();
        List<double> s1107 = new List<double>();
        List<double> s1108 = new List<double>();
        List<double> coefficient = new List<double>();

        // Liste pour stocker les coefficients
        List<double> coefficients = new List<double>();

        // Configuration de CsvHelper pour utiliser le point-virgule comme délimiteur
        var csvConfig = new CsvHelper.Configuration.CsvConfiguration(CultureInfo.InvariantCulture)
        {
            Delimiter = ";"
        };

        // Lecture du fichier CSV
        using (var reader = new StreamReader(csvFilePath)) //changer par filePath quand la connexion c# web se fera
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
                    s1101.Add(Convert.ToDouble(record.S1101));
                    s1102.Add(Convert.ToDouble(record.S1102));
                    s1103.Add(Convert.ToDouble(record.S1103));
                    s1104.Add(Convert.ToDouble(record.S1104));
                    s1105a.Add(Convert.ToDouble(record.S1105A));
                    s1105b.Add(Convert.ToDouble(record.S1105B));
                    s1106.Add(Convert.ToDouble(record.S1106));
                    s1107.Add(Convert.ToDouble(record.S1107));
                    s1108.Add(Convert.ToDouble(record.S1108));
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
                            coefficients.Add(Convert.ToDouble(property.Value));
                        }
                    }
                    
                    // Sortir de la boucle une fois que le record souhaité est trouvé
                    break;
                }

                currentIndex++;
                
            }
        }

        // Création d'une liste pour stocker toutes les listes s110*
        List<List<double>> toutesLesListes = new List<List<double>>
            { s1101, s1102, s1103, s1104, s1105a, s1105b, s1106, s1107, s1108 };
        // Liste pour stocker toutes les listes notesDeLaPosition
        List<List<double>> notesEtudiants = new List<List<double>>();


        // Boucle pour parcourir chaque position dans les listes s110*
        for (int position = 0; position < toutesLesListes[0].Count; position++)
        {
            // Liste pour stocker les notes de la position actuelle
            List<double> notesDeLaPosition = new List<double>();

            // Boucle pour parcourir chaque liste s110* et ajouter la note à la liste
            foreach (var liste in toutesLesListes)
            {
                notesDeLaPosition.Add(liste.Count > position ? liste[position] : 0);
            }

            // Ajouter la liste notesDeLaPosition à la liste principale
            notesEtudiants.Add(notesDeLaPosition);

        }
        
        //envoi des listes noms, prénoms, coefficients et notesEtudiants au web
    }
}
