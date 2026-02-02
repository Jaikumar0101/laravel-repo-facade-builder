<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFacadeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:facade {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new facade class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Explode input to handle subfolders (e.g., Services/Payment)
        $parts = explode('/', $name);
        $className = array_pop($parts); // Last part is class name
        $subPath = $parts ? implode('/', $parts) . '/' : ''; // Subfolder path for directories
        $namespace = $parts ? '\\' . implode('\\', $parts) : ''; // Namespace suffix for subfolders

        // Ensure the directory exists within app/Facades
        $directory = app_path('Facades/' . $subPath);
        File::ensureDirectoryExists($directory);

        // Define the facade content with proper namespace
        $facade = "<?php

namespace App\\Facades{$namespace};

use Illuminate\\Support\\Facades\\Facade;

class {$className} extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return '{$this->convertToSnakeCase($className)}';
    }
}
";

        // Save the file inside the directory
        File::put("{$directory}{$className}.php", $facade);

        // Inform user of success
        $this->info("Facade {$name} created successfully!");
        $this->line("  - {$directory}{$className}.php");
        $this->newLine();
        $this->comment("Don't forget to:");
        $this->line("  1. Create the underlying service class");
        $this->line("  2. Bind it in a service provider:");
        $this->line("     \$this->app->singleton('{$this->convertToSnakeCase($className)}', function () {");
        $this->line("         return new YourServiceClass();");
        $this->line("     });");
    }

    /**
     * Convert class name to snake_case for facade accessor.
     */
    protected function convertToSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
