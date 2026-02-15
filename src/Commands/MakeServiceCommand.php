<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Explode input to handle subfolders (e.g., Payment/Stripe/StripePayment)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Services
        $directory = app_path('Services/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the service class content with proper namespace
        $service = "<?php

namespace App\\Services{$namespace};

class {$className}Service
{
    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        //
    }

    // Methods...
}
";

        // Save the file inside the directory
        File::put("{$directory}{$className}Service.php", $service);

        // Inform user of success
        $this->info("Service {$name} created successfully!");
        $this->line("  - {$directory}{$className}Service.php");
    }
}
