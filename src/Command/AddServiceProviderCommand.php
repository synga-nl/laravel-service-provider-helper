<?php
/**
 * Synga Inheritance Finder
 * @author      Roy Pouls
 * @copytright  2016 Roy Pouls / Synga (http://www.synga.nl)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/synga-nl/inheritance-finder
 */

namespace Synga\ServiceProviderHelper\Command;


use Illuminate\Console\Command;
use Synga\ConsoleAbstraction\ConsoleInteraction;
use Synga\ConsoleAbstraction\Laravel\Input;
use Synga\ConsoleAbstraction\Laravel\Output;
use Synga\ServiceProviderHelper\ServiceProviderAdder;
use Synga\ServiceProviderHelper\ServiceProviderFileParser;
use Synga\ServiceProviderHelper\ServiceProviderService;

class AddServiceProviderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-provider:add {--c|composer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new service providers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ServiceProviderAdder $finder, ServiceProviderFileParser $serviceProviderFileParser) {
        $consoleInteraction = new ConsoleInteraction(new Input($this->input), new Output($this));

        $serviceProviderService = new ServiceProviderService($consoleInteraction, $finder, $serviceProviderFileParser);
        $serviceProviderService->handle();
    }
}