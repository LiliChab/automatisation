using Microsoft.AspNetCore.Mvc;

[ApiController]
[Route("api/etudiants")]
public class EtudiantsController : ControllerBase
{
    [HttpGet]
    public IActionResult GetEtudiants()
    {

        while (WatchFolderService.noms.Count == 0)
        {
            Thread.Sleep(100); // Attendre 100 millisecondes avant de vérifier à nouveau
        }
        
        var etudiants = new
        {
            Noms = WatchFolderService.noms,
            Prenoms =  WatchFolderService.prenoms,
            Coefficients =  WatchFolderService.coefficients,
            NotesEtudiants =  WatchFolderService.notesEtudiants
        };

        return Ok(etudiants);
    }
}
