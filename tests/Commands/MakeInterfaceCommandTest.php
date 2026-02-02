<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeInterfaceCommandTest extends TestCase
{
    /** @test */
    public function it_creates_interface_file()
    {
        $this->artisan('make:interface', ['name' => 'PaymentGateway'])
            ->expectsOutput('Interface PaymentGatewayInterface created successfully!')
            ->assertExitCode(0);

        $interfacePath = app_path('Interfaces/PaymentGatewayInterface.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileContains($interfacePath, 'namespace App\Interfaces;');
        $this->assertFileContains($interfacePath, 'interface PaymentGatewayInterface');
    }

    /** @test */
    public function it_appends_interface_suffix_if_not_present()
    {
        $this->artisan('make:interface', ['name' => 'Logger'])
            ->expectsOutput('Interface LoggerInterface created successfully!')
            ->assertExitCode(0);

        $interfacePath = app_path('Interfaces/LoggerInterface.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileContains($interfacePath, 'interface LoggerInterface');
    }

    /** @test */
    public function it_does_not_duplicate_interface_suffix()
    {
        $this->artisan('make:interface', ['name' => 'CacheInterface'])
            ->expectsOutput('Interface CacheInterface created successfully!')
            ->assertExitCode(0);

        $interfacePath = app_path('Interfaces/CacheInterface.php');

        $this->assertFileExists($interfacePath);
        
        $content = File::get($interfacePath);
        // Should not have InterfaceInterface
        $this->assertStringNotContainsString('InterfaceInterface', $content);
        $this->assertStringContainsString('interface CacheInterface', $content);
    }

    /** @test */
    public function it_creates_interface_with_subfolders()
    {
        $this->artisan('make:interface', ['name' => 'Contracts/Payment/Gateway'])
            ->assertExitCode(0);

        $interfacePath = app_path('Interfaces/Contracts/Payment/GatewayInterface.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileContains($interfacePath, 'namespace App\Interfaces\Contracts\Payment;');
        $this->assertFileContains($interfacePath, 'interface GatewayInterface');
    }

    /** @test */
    public function it_includes_method_comment_placeholder()
    {
        $this->artisan('make:interface', ['name' => 'Cacheable'])
            ->assertExitCode(0);

        $interfacePath = app_path('Interfaces/CacheableInterface.php');

        $this->assertFileContains($interfacePath, '// Define your methods here');
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Interfaces/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:interface', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_handles_deep_nesting()
    {
        $this->artisan('make:interface', ['name' => 'Level1/Level2/Level3/DeepInterface'])
            ->assertExitCode(0);

        // Since "DeepInterface" already ends with "Interface", it shouldn't be doubled
        $interfacePath = app_path('Interfaces/Level1/Level2/Level3/DeepInterface.php');

        $this->assertFileExists($interfacePath);
        $this->assertFileContains($interfacePath, 'namespace App\Interfaces\Level1\Level2\Level3;');
        $this->assertFileContains($interfacePath, 'interface DeepInterface');
    }
}
