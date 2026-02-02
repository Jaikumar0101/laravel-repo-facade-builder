<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeTraitCommandTest extends TestCase
{
    /** @test */
    public function it_creates_trait_file()
    {
        $this->artisan('make:trait', ['name' => 'HasUuid'])
            ->expectsOutput('Trait HasUuid created successfully!')
            ->assertExitCode(0);

        $traitPath = app_path('Traits/HasUuid.php');

        $this->assertFileExists($traitPath);
        $this->assertFileContains($traitPath, 'namespace App\Traits;');
        $this->assertFileContains($traitPath, 'trait HasUuid');
    }

    /** @test */
    public function it_creates_trait_with_subfolders()
    {
        $this->artisan('make:trait', ['name' => 'Concerns/HasSlug'])
            ->expectsOutput('Trait Concerns/HasSlug created successfully!')
            ->assertExitCode(0);

        $traitPath = app_path('Traits/Concerns/HasSlug.php');

        $this->assertFileExists($traitPath);
        $this->assertFileContains($traitPath, 'namespace App\Traits\Concerns;');
        $this->assertFileContains($traitPath, 'trait HasSlug');
    }

    /** @test */
    public function it_creates_trait_with_deep_nesting()
    {
        $this->artisan('make:trait', ['name' => 'Models/Concerns/Timestamps'])
            ->assertExitCode(0);

        $traitPath = app_path('Traits/Models/Concerns/Timestamps.php');

        $this->assertFileExists($traitPath);
        $this->assertFileContains($traitPath, 'namespace App\Traits\Models\Concerns;');
        $this->assertFileContains($traitPath, 'trait Timestamps');
    }

    /** @test */
    public function it_includes_method_placeholder_comment()
    {
        $this->artisan('make:trait', ['name' => 'Cacheable'])
            ->assertExitCode(0);

        $traitPath = app_path('Traits/Cacheable.php');

        $this->assertFileContains($traitPath, '// Define your methods and properties here');
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Traits/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:trait', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_handles_single_word_trait_names()
    {
        $this->artisan('make:trait', ['name' => 'Sortable'])
            ->assertExitCode(0);

        $traitPath = app_path('Traits/Sortable.php');

        $this->assertFileExists($traitPath);
        $this->assertFileContains($traitPath, 'trait Sortable');
    }

    /** @test */
    public function it_handles_trait_names_with_multiple_capitals()
    {
        $this->artisan('make:trait', ['name' => 'HasAPITokens'])
            ->assertExitCode(0);

        $traitPath = app_path('Traits/HasAPITokens.php');

        $this->assertFileExists($traitPath);
        $this->assertFileContains($traitPath, 'trait HasAPITokens');
    }

    /** @test */
    public function it_creates_multiple_traits_independently()
    {
        $this->artisan('make:trait', ['name' => 'Trait1'])
            ->assertExitCode(0);
        
        $this->artisan('make:trait', ['name' => 'Trait2'])
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Traits/Trait1.php'));
        $this->assertFileExists(app_path('Traits/Trait2.php'));

        $this->assertFileContains(app_path('Traits/Trait1.php'), 'trait Trait1');
        $this->assertFileContains(app_path('Traits/Trait2.php'), 'trait Trait2');
    }
}
