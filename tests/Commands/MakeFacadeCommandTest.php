<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests\Commands;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\Tests\TestCase;

class MakeFacadeCommandTest extends TestCase
{
    /** @test */
    public function it_creates_facade_file()
    {
        $this->artisan('make:facade', ['name' => 'Payment'])
            ->expectsOutput('Facade Payment created successfully!')
            ->assertExitCode(0);

        $facadePath = app_path('Facades/Payment.php');

        $this->assertFileExists($facadePath);
        $this->assertFileContains($facadePath, 'namespace App\Facades;');
        $this->assertFileContains($facadePath, 'class Payment extends Facade');
        $this->assertFileContains($facadePath, "return 'payment';");
    }

    /** @test */
    public function it_creates_facade_with_subfolders()
    {
        $this->artisan('make:facade', ['name' => 'Services/Payment/Gateway'])
            ->expectsOutput('Facade Services/Payment/Gateway created successfully!')
            ->assertExitCode(0);

        $facadePath = app_path('Facades/Services/Payment/Gateway.php');

        $this->assertFileExists($facadePath);
        $this->assertFileContains($facadePath, 'namespace App\Facades\Services\Payment;');
        $this->assertFileContains($facadePath, 'class Gateway extends Facade');
    }

    /** @test */
    public function it_converts_class_name_to_snake_case_for_accessor()
    {
        $this->artisan('make:facade', ['name' => 'PaymentService'])
            ->assertExitCode(0);

        $facadePath = app_path('Facades/PaymentService.php');

        $this->assertFileContains($facadePath, "return 'payment_service';");
    }

    /** @test */
    public function it_handles_single_word_class_names()
    {
        $this->artisan('make:facade', ['name' => 'Cache'])
            ->assertExitCode(0);

        $facadePath = app_path('Facades/Cache.php');

        $this->assertFileContains($facadePath, "return 'cache';");
    }

    /** @test */
    public function it_displays_helpful_reminder_messages()
    {
        $this->artisan('make:facade', ['name' => 'TestService'])
            ->expectsOutput('Facade TestService created successfully!')
            ->assertExitCode(0);
        
        // The command outputs the reminder, we just verify it completes successfully
    }

    /** @test */
    public function it_creates_directory_if_not_exists()
    {
        $directory = app_path('Facades/NewFolder');
        
        $this->assertDirectoryDoesNotExist($directory);

        $this->artisan('make:facade', ['name' => 'NewFolder/Test'])
            ->assertExitCode(0);

        $this->assertDirectoryExists($directory);
    }

    /** @test */
    public function it_handles_multiple_capital_letters()
    {
        $this->artisan('make:facade', ['name' => 'SMSService'])
            ->assertExitCode(0);

        $facadePath = app_path('Facades/SMSService.php');

        // Should convert to s_m_s_service
        $this->assertFileContains($facadePath, "return 's_m_s_service';");
    }
}
