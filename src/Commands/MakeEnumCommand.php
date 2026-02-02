<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEnumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name} {--type= : The backing type for the enum (string, int)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enum class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $type = $this->option('type');

        // Explode input to handle subfolders (e.g., Constants/Status)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Enums
        $directory = app_path('Enums/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the enum content with proper namespace and backing type
        $backingType = in_array($type, ['string', 'int']) ? ": {$type}" : '';
        
        $enum = "<?php

namespace App\\Enums{$namespace};

enum {$className}{$backingType}
{
    // Define your cases here
    // Example for string enum:
    // case ACTIVE = 'active';
    // case INACTIVE = 'inactive';
    
    // Example for int enum:
    // case PENDING = 1;
    // case APPROVED = 2;
    
    // Example for simple enum (no backing value):
    // case DRAFT;
    // case PUBLISHED;
}
";

        // Save the file inside the directory
        File::put("{$directory}{$className}.php", $enum);

        // Inform user of success
        $this->info("Enum {$name} created successfully!");
        $this->line("  - {$directory}{$className}.php");
        if ($backingType) {
            $this->line("  - Backing type: {$type}");
        }
    }
}
