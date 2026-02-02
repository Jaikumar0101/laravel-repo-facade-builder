<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository and interface';

    /**
     * Aliases for the command.
     *
     * @var array
     */
    protected $aliases = [
        'make:repo',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Explode input to handle subfolders (e.g., Accounting/Bill/CreditNote)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Repositories
        $directory = app_path('Repositories/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the interface content with proper namespace
        $interface = "<?php

namespace App\\Repositories{$namespace};

interface {$className}RepositoryInterface
{
    // Methods...
}
";

        // Define the repository class content with proper namespace and interface implementation
        $repository = "<?php

namespace App\\Repositories{$namespace};

class {$className}Repository implements {$className}RepositoryInterface
{
    // Implementation...
}
";

        // Save the files inside the directory
        File::put("{$directory}{$className}RepositoryInterface.php", $interface);
        File::put("{$directory}{$className}Repository.php", $repository);

        // Inform user of success
        $this->info("Repository and interface for {$name} created successfully!");
        $this->line("  - {$directory}{$className}RepositoryInterface.php");
        $this->line("  - {$directory}{$className}Repository.php");
    }
}
