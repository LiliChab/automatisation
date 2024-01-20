// See https://aka.ms/new-console-template for more information
using Microsoft.AspNetCore.Hosting;
using Microsoft.Extensions.Hosting;

class Program
{
    static async Task Main(string[] args)
    {
        var counter = 0;
        var max = args.Length is not 0 ? Convert.ToInt32(args[0]) : -1;
        while (max is -1 || counter < max)
        {
            ++counter;
            CreateHostBuilder(args).Build().Run();
            
            await Task.Delay(TimeSpan.FromMilliseconds(1_000));
        }

        
    }

    public static IHostBuilder CreateHostBuilder(string[] args) =>
        Host.CreateDefaultBuilder(args)
            .ConfigureWebHostDefaults(webBuilder =>
            {
                webBuilder.UseStartup<Startup>();
            });

}




