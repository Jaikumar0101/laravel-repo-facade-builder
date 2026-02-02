<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder\Tests;

use Illuminate\Support\Facades\File;
use Jaikumar0101\LaravelRepoFacadeBuilder\LaravelRepoFacadeBuilderServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clean up any test files before each test
        $this->cleanTestDirectories();
    }

    protected function tearDown(): void
    {
        // Clean up test files after each test
        $this->cleanTestDirectories();
        
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelRepoFacadeBuilderServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Clean up test directories
     */
    protected function cleanTestDirectories(): void
    {
        $directories = [
            app_path('Repositories'),
            app_path('Facades'),
            app_path('Enums'),
            app_path('Interfaces'),
            app_path('Traits'),
        ];

        foreach ($directories as $directory) {
            if (File::exists($directory)) {
                File::deleteDirectory($directory);
            }
        }
    }

    /**
     * Assert that a file exists and contains the expected content
     */
    protected function assertFileContains(string $path, string $expectedContent): void
    {
        $this->assertFileExists($path);
        $actualContent = File::get($path);
        $this->assertStringContainsString($expectedContent, $actualContent);
    }

    /**
     * Assert that a file exists and matches the expected content exactly
     */
    protected function assertFileContentEquals(string $path, string $expectedContent): void
    {
        $this->assertFileExists($path);
        $actualContent = File::get($path);
        $this->assertEquals($expectedContent, $actualContent);
    }
}
