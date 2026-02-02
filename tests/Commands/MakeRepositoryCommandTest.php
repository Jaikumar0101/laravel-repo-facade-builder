<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeRepositoryCommandTest extends TestCase
{
    /** @test */
    public function it_creates_repository_and_interface_files()
    {
        $this->artisan('make:repository', ['name' => 'User'])
            ->expectsOutput('Repository and interface for User created successfully!')
            ->assertExitCode(0);

        $interfacePath = app_path('Repositories/UserRepositoryInterface.php');
        $repositoryPath = app_path('Repositories/UserRepository.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileExists($repositoryPath);

        // Check interface content
        $this->assertFileContains($interfacePath, 'namespace App\Repositories;');
        $this->assertFileContains($interfacePath, 'interface UserRepositoryInterface');

        // Check repository content
        $this->assertFileContains($repositoryPath, 'namespace App\Repositories;');
        $this->assertFileContains($repositoryPath, 'class UserRepository implements UserRepositoryInterface');
    }

    /** @test */
    public function it_creates_repository_with_subfolders()
    {
        $this->artisan('make:repository', ['name' => 'Accounting/Bill/CreditNote'])
            ->expectsOutput('Repository and interface for Accounting/Bill/CreditNote created successfully!')
            ->assertExitCode(0);

        $interfacePath = app_path('Repositories/Accounting/Bill/CreditNoteRepositoryInterface.php');
        $repositoryPath = app_path('Repositories/Accounting/Bill/CreditNoteRepository.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileExists($repositoryPath);

        // Check namespace includes subfolder
        $this->assertFileContains($interfacePath, 'namespace App\Repositories\Accounting\Bill;');
        $this->assertFileContains($interfacePath, 'interface CreditNoteRepositoryInterface');

        $this->assertFileContains($repositoryPath, 'namespace App\Repositories\Accounting\Bill;');
        $this->assertFileContains($repositoryPath, 'class CreditNoteRepository implements CreditNoteRepositoryInterface');
    }

    /** @test */
    public function it_creates_repository_with_single_subfolder()
    {
        $this->artisan('make:repository', ['name' => 'Admin/User'])
            ->assertExitCode(0);

        $interfacePath = app_path('Repositories/Admin/UserRepositoryInterface.php');
        $repositoryPath = app_path('Repositories/Admin/UserRepository.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileExists($repositoryPath);

        $this->assertFileContains($interfacePath, 'namespace App\Repositories\Admin;');
        $this->assertFileContains($repositoryPath, 'namespace App\Repositories\Admin;');
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Repositories/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:repository', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_overwrites_existing_files()
    {
        // Create initial files
        $this->artisan('make:repository', ['name' => 'Product'])
            ->assertExitCode(0);

        $repositoryPath = app_path('Repositories/ProductRepository.php');
        $originalContent = File::get($repositoryPath);

        // Create again (should overwrite)
        $this->artisan('make:repository', ['name' => 'Product'])
            ->assertExitCode(0);

        $newContent = File::get($repositoryPath);
        $this->assertEquals($originalContent, $newContent);
    }
}
