<?php
namespace Synga\ServiceProviderHelper;

use PhpParser\ParserFactory;
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
        $this->app->when('Synga\ServiceProviderHelper\ServiceProviderAdder')
            ->needs('Synga\InheritanceFinder\InheritanceFinderInterface')
            ->give(function () {
                $config = new FileConfig();
                $config->setApplicationRoot(base_path());
                $config->setCacheDirectory(storage_path('synga/class_cache'));

                return InheritanceFinderFactory::getInheritanceFinder($config);
            });

        $this->app->singleton('PhpParser\Parser', function(){
            return (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        });

        $this->commands([
            \Synga\ServiceProviderHelper\Command\AddServiceProviderCommand::class
        ]);
    }

}