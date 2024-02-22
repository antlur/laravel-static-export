<?php

namespace Antlur\Export;

use Antlur\Export\Commands\ExportCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExportServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-static-export')
            ->hasConfigFile()
            ->hasCommand(ExportCommand::class);
    }
}
