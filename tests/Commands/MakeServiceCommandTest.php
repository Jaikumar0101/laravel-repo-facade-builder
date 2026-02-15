<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeServiceCommandTest extends TestCase
{
    /** @test */
    public function it_creates_service_file()
    {
        $this->artisan('make:service', ['name' => 'User'])
            ->expectsOutput('Service User created successfully!')
            ->assertExitCode(0);

        $servicePath = app_path('Services/UserService.php');

        $this->assertFileExists($servicePath);

        // Check service content
        $this->assertFileContains($servicePath, 'namespace App\Services;');
        $this->assertFileContains($servicePath, 'class UserService');
    }

    /** @test */
    public function it_creates_service_with_subfolders()
    {
        $this->artisan('make:service', ['name' => 'Payment/Stripe/StripePayment'])
            ->expectsOutput('Service Payment/Stripe/StripePayment created successfully!')
            ->assertExitCode(0);

        $servicePath = app_path('Services/Payment/Stripe/StripePaymentService.php');

        $this->assertFileExists($servicePath);

        // Check namespace includes subfolder
        $this->assertFileContains($servicePath, 'namespace App\Services\Payment\Stripe;');
        $this->assertFileContains($servicePath, 'class StripePaymentService');
    }

    /** @test */
    public function it_creates_service_with_single_subfolder()
    {
        $this->artisan('make:service', ['name' => 'Admin/User'])
            ->assertExitCode(0);

        $servicePath = app_path('Services/Admin/UserService.php');

        $this->assertFileExists($servicePath);

        $this->assertFileContains($servicePath, 'namespace App\Services\Admin;');
        $this->assertFileContains($servicePath, 'class UserService');
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Services/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:service', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_overwrites_existing_files()
    {
        // Create initial file
        $this->artisan('make:service', ['name' => 'Product'])
            ->assertExitCode(0);

        $servicePath = app_path('Services/ProductService.php');
        $originalContent = File::get($servicePath);

        // Create again (should overwrite)
        $this->artisan('make:service', ['name' => 'Product'])
            ->assertExitCode(0);

        $newContent = File::get($servicePath);
        $this->assertEquals($originalContent, $newContent);
    }
}
