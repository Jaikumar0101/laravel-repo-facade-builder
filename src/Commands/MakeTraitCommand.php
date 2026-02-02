<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTraitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Explode input to handle subfolders (e.g., Concerns/HasUuid)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Traits
        $directory = app_path('Traits/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the trait content with proper namespace
        $trait = "<?php

namespace App\\Traits{$namespace};

trait {$className}
{
    // Define your methods and properties here
}
";

        // Save the file inside the directory
        File::put("{$directory}{$className}.php", $trait);

        // Inform user of success
        $this->info("Trait {$name} created successfully!");
        $this->line("  - {$directory}{$className}.php");
    }
}
