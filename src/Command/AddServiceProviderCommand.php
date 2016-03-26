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
use Synga\ServiceProviderHelper\ServiceProviderAdder;
use Synga\ServiceProviderHelper\ServiceProviderFileParser;

class AddServiceProviderCommand extends Command
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
    public function handle(ServiceProviderAdder $finder, ServiceProviderFileParser $fileParser) {
        $configFilePath = config_path('app.php');
        $differences = $finder->find();

        $toBeAdded = [];
        while (true) {
            $output = $this->output->choice('Which service provider do you want to add?', array_merge(array_diff($differences['first_diff_second'], $toBeAdded), ['exit']));
            if ($output == 'exit') {
                break;
            } else {
                $toBeAdded[] = $output;
            }
        }

        if (count($toBeAdded)) {
            $code = $fileParser->parse($toBeAdded, file_get_contents($configFilePath));
            file_put_contents($configFilePath, "<?php\r\n" . $code);

            $this->output->note(count($toBeAdded) . ' service providers added');
        } else {
            $this->output->warning('No service providers are added because none were selected');
        }
    }
}