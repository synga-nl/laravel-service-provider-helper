<?php
namespace Synga\ServiceProviderHelper;

use Synga\InheritanceFinder\File\FileConfig;
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
        $this->app->when('Synga\ServiceProviderHelper\ServiceProviderFinder')
            ->needs('Synga\InheritanceFinder\InheritanceFinderInterface')
            ->give(function () {
                $config = new FileConfig();
                $config->setApplicationRoot(base_path());
                $config->setCacheDirectory(storage_path('class_cache'));

                return InheritanceFinderFactory::getInheritanceFinder($config);
            });

        $this->commands([
            \Synga\ServiceProviderHelper\Command\DetectServiceProviderCommand::class
        ]);
    }

}