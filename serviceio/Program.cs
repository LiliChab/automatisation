using System;
using System.Collections.Generic;
using System.Globalization;
using System.IO;
using CsvHelper;

class Program
{
    static void Main()
    {
        Console.WriteLine("bonjour");
        // Chemin du dossier à surveiller
        string folderPath = @"/app/fichiers/";

        // Créer une instance de FileSystemWatcher
        FileSystemWatcher watcher = new FileSystemWatcher(folderPath);

        // Activer les notifications pour les changements de création de fichiers
        watcher.NotifyFilter = NotifyFilters.FileName | NotifyFilters.LastWrite;

        // Événement déclenché lorsqu'un fichier est créé ou modifié
        watcher.Created += OnFileCreated;

        // Démarrer la surveillance
        watcher.EnableRaisingEvents = true;

        Console.WriteLine("Service C# en cours d'exécution. Appuyez sur Enter pour quitter.");
        Console.ReadLine();
    }

    private static void OnFileCreated(object sender, FileSystemEventArgs e)
    {
        // Appeler la méthode de traitement du fichier
        ProcessCsvFile(e.FullPath);
    }

    private static void ProcessCsvFile(string filePath)
    {
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

        // Configuration de CsvHelper pour utiliser le point-virgule comme délimiteur
        var csvConfig = new CsvHelper.Configuration.CsvConfiguration(CultureInfo.InvariantCulture)
        {
            Delimiter = ";"
        };

        // Lecture du fichier CSV
        using (var reader = new StreamReader(filePath))
        using (var csv = new CsvReader(reader, csvConfig))
        {
            // Lecture des enregistrements
            var records = csv.GetRecords<dynamic>();

            // Parcours des enregistrements et extraction des noms et prénoms
            foreach (var record in records)
            {
                // Vérification pour s'assurer que le champ "Nom" n'est pas vide
                if (string.IsNullOrWhiteSpace(record.Nom))
                {
                    break; // Sortir de la boucle si le champ "Nom" est vide
                }

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
        }

        // Afficher les notes d'un étudiant
        Console.WriteLine("Liste des notes d'un étudiant :");
        for (int i = 0; i < noms.Count; i++)
        {
            Console.WriteLine($"{noms[i]} {prenoms[i]} {s1101[i]} {s1102[i]} {s1103[i]} {s1104[i]} {s1105a[i]} {s1105b[i]} {s1106[i]} {s1107[i]} {s1108[i]}");
        }
    }
}
