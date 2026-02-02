<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeEnumCommandTest extends TestCase
{
    /** @test */
    public function it_creates_enum_file_without_backing_type()
    {
        $this->artisan('make:enum', ['name' => 'Status'])
            ->expectsOutput('Enum Status created successfully!')
            ->assertExitCode(0);

        $enumPath = app_path('Enums/Status.php');

        $this->assertFileExists($enumPath);
        $this->assertFileContains($enumPath, 'namespace App\Enums;');
        $this->assertFileContains($enumPath, 'enum Status');
        // Should NOT have a colon (no backing type)
        $content = File::get($enumPath);
        $this->assertStringNotContainsString('enum Status:', $content);
    }

    /** @test */
    public function it_creates_enum_with_string_backing_type()
    {
        $this->artisan('make:enum', ['name' => 'OrderStatus', '--type' => 'string'])
            ->expectsOutput('Enum OrderStatus created successfully!')
            ->assertExitCode(0);

        $enumPath = app_path('Enums/OrderStatus.php');

        $this->assertFileExists($enumPath);
        $this->assertFileContains($enumPath, 'enum OrderStatus: string');
    }

    /** @test */
    public function it_creates_enum_with_int_backing_type()
    {
        $this->artisan('make:enum', ['name' => 'Priority', '--type' => 'int'])
            ->expectsOutput('Enum Priority created successfully!')
            ->assertExitCode(0);

        $enumPath = app_path('Enums/Priority.php');

        $this->assertFileExists($enumPath);
        $this->assertFileContains($enumPath, 'enum Priority: int');
    }

    /** @test */
    public function it_creates_enum_with_subfolders()
    {
        $this->artisan('make:enum', ['name' => 'Constants/Payment/Status', '--type' => 'string'])
            ->assertExitCode(0);

        $enumPath = app_path('Enums/Constants/Payment/Status.php');

        $this->assertFileExists($enumPath);
        $this->assertFileContains($enumPath, 'namespace App\Enums\Constants\Payment;');
        $this->assertFileContains($enumPath, 'enum Status: string');
    }

    /** @test */
    public function it_ignores_invalid_backing_types()
    {
        $this->artisan('make:enum', ['name' => 'Test', '--type' => 'float'])
            ->assertExitCode(0);

        $enumPath = app_path('Enums/Test.php');

        $content = File::get($enumPath);
        // Should not have backing type since float is invalid
        $this->assertStringNotContainsString('enum Test:', $content);
        $this->assertStringContainsString('enum Test', $content);
    }

    /** @test */
    public function it_includes_example_cases_in_generated_enum()
    {
        $this->artisan('make:enum', ['name' => 'Example', '--type' => 'string'])
            ->assertExitCode(0);

        $enumPath = app_path('Enums/Example.php');

        $this->assertFileContains($enumPath, '// Define your cases here');
        $this->assertFileContains($enumPath, '// Example for string enum:');
        $this->assertFileContains($enumPath, '// Example for int enum:');
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Enums/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:enum', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_defaults_to_no_backing_type_when_option_not_provided()
    {
        $this->artisan('make:enum', ['name' => 'SimpleEnum'])
            ->assertExitCode(0);

        $enumPath = app_path('Enums/SimpleEnum.php');
        $content = File::get($enumPath);

        // Check it's a simple enum without backing type
        $this->assertStringContainsString('enum SimpleEnum', $content);
        $this->assertStringNotContainsString('enum SimpleEnum:', $content);
    }
}
