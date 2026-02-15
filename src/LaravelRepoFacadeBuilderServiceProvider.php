<?php

namespace Jaikumar0101\LaravelRepoFacadeBuilder;

use Illuminate\Support\ServiceProvider;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeRepositoryCommand;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeFacadeCommand;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeEnumCommand;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeInterfaceCommand;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeTraitCommand;
use Jaikumar0101\LaravelRepoFacadeBuilder\Commands\MakeServiceCommand;

class LaravelRepoFacadeBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
                MakeFacadeCommand::class,
                MakeEnumCommand::class,
                MakeInterfaceCommand::class,
                MakeTraitCommand::class,
                MakeServiceCommand::class,
            ]);
        }
    }
}
