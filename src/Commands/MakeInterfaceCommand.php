<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeInterfaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Explode input to handle subfolders (e.g., Contracts/Payment)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        
        // Ensure the interface name ends with "Interface" before processing subpaths
        if (!str_ends_with($className, 'Interface')) {
            $className .= 'Interface';
        }
        
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Interfaces (or app/Contracts)
        $directory = app_path('Interfaces/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the interface content with proper namespace
        $interface = "<?php

namespace App\\Interfaces{$namespace};

interface {$className}
{
    // Define your methods here
}
";

        // Save the file inside the directory
        File::put("{$directory}{$className}.php", $interface);

        // Inform user of success
        $this->info("Interface {$className} created successfully!");
        $this->line("  - {$directory}{$className}.php");
    }
}
