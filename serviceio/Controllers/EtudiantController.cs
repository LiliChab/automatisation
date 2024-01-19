using Microsoft.AspNetCore.Mvc;

[ApiController]
[Route("api/etudiants")]
public class EtudiantsController : ControllerBase
{
    [HttpGet]
    public IActionResult GetEtudiants()
    {
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
