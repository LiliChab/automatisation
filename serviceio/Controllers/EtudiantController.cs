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
            Noms = Program.noms,
            Prenoms = Program.prenoms,
            Coefficients = Program.coefficients,
            NotesEtudiants = Program.notesEtudiants
        };

        return Ok(etudiants);
    }
}
