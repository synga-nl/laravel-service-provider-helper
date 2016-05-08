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
                $config->setCacheDirectory(\Config::get('providers.inheritance_finder_storage_path'));

                return InheritanceFinderFactory::getInheritanceFinder($config);
            });

        $this->app->singleton('PhpParser\Parser', function(){
            return (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        });

        $this->commands([
            \Synga\ServiceProviderHelper\Command\AddServiceProviderCommand::class
        ]);
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Laravel/config/providers.php' => config_path('providers.php'),
        ]);
    }
}