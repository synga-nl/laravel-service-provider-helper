<?php
namespace Synga\ServiceProviderHelper;

use Synga\InheritanceFinder\InheritanceFinderFactory;

/**
 * Class ServiceProviderHelperServiceProvider
 * @package Synga\ServiceProviderHelper
 */
class ServiceProviderHelperServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Registers c
     */
    public function register() {
        $this->app->when('Synga\ServiceProviderHelper\ServiceProviderHelper')
            ->needs('Synga\InheritanceFinder\InheritanceFinderInterface')
            ->give(function () {
                return InheritanceFinderFactory::getInheritanceFinder(storage_path('class_cache'));
            });

        $this->commands([
            \Synga\ServiceProviderHelper\Command\DetectServiceProviderCommand::class
        ]);
    }

}