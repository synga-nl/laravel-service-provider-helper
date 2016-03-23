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
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Synga\ServiceProviderHelper\Parser\PrettyPrinter\ServiceProviderPrettyPrinter;
use Synga\ServiceProviderHelper\Parser\Visitor\AddServiceProviderVisitor;
use Synga\ServiceProviderHelper\ServiceProviderFinder;

class DetectServiceProviderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service-provider:add';

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
    public function handle(ServiceProviderFinder $finder) {
        $differences = $finder->find();

        $serviceProviderPath      = storage_path('service_provider');
        $serviceProviderCachePath = $serviceProviderPath . '/service_provider.cache';
        $configFilePath = config_path('app.php');


        if (!file_exists($serviceProviderPath) || !file_exists($serviceProviderCachePath)) {
            mkdir($serviceProviderPath);
            file_put_contents($serviceProviderCachePath, serialize([]));
        }

        $cache = unserialize(file_get_contents($serviceProviderCachePath));

        if (!empty($cache['ignore'])) {
            foreach ($differences['first_diff_second'] as $key => $serviceProvider) {
                if (in_array($serviceProvider, $cache['ignore'])) {
                    unset($differences['first_diff_second'][$key]);
                }
            }
        }

        $toBeAdded = [];

        while (true) {
            $output = $this->output->choice('Which service provider do you want to add?', array_merge($differences['first_diff_second'], ['exit']));

            if ($output == 'exit') {
                break;
            } else {
                $toBeAdded[] = $output;
            }
        }

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $nodes = $parser->parse(file_get_contents($configFilePath));

        $traverser = new NodeTraverser();

        $addServiceProviderVisitor = new AddServiceProviderVisitor();
        $addServiceProviderVisitor->setServiceProvider(array_merge(\Config::get('app.providers'), $toBeAdded));
        $traverser->addVisitor($addServiceProviderVisitor);

        $parsedNodes = $traverser->traverse($nodes);

        $prettyPrinter = new ServiceProviderPrettyPrinter(['shortArraySyntax' => true]);
        $code = $prettyPrinter->prettyPrint($parsedNodes);

        file_put_contents($configFilePath, "<?php\r\n" . $code);
    }
}