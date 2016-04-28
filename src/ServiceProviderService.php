<?php
namespace Synga\ServiceProviderHelper;

use Synga\ConsoleAbstraction\Contracts\ConsoleInteractionInterface;

class ServiceProviderService
{
    /**
     * @var \Synga\ConsoleAbstraction\Contracts\InputInterface
     */
    protected $input;

    /**
     * @var \Synga\ConsoleAbstraction\Contracts\OutputInterface
     */
    protected $output;

    /**
     * @var ServiceProviderAdder
     */
    protected $finder;

    /**
     * @var ServiceProviderFileParser
     */
    protected $fileParser;

    public function __construct(ConsoleInteractionInterface $consoleInteraction, ServiceProviderAdder $finder, ServiceProviderFileParser $fileParser) {
        $this->consoleInteraction = $consoleInteraction;
        $this->finder             = $finder;
        $this->fileParser         = $fileParser;
        $this->input              = $consoleInteraction->getInput();
        $this->output             = $consoleInteraction->getOutput();
    }

    public function handle() {
        $configFilePath = config_path('app.php');
        $composer = $this->input->getOption('composer');
        $differences    = $this->finder->find($composer);

        if ($this->input->isInteractive() === false) {
            $this->output->warning("We can't ask you some very nice questions. We will abort now!");

            return;
        }

        $toBeAdded = [];
        while (true) {
            $serviceProviderList = $this->getServiceProviderList($differences['first_diff_second'], $toBeAdded);
            if (empty($serviceProviderList)) {
                break;
            }
            $output = $this->output->choice('Which service provider do you want to add?', array_merge($serviceProviderList, ['exit']));
            if ($output == 'exit') {
                break;
            } else {
                $toBeAdded[] = $output;
            }
        }

        if ($composer === true) {
            $this->finder->writeCache($this->finder->find(false)['first_diff_second']);
        }

        if (count($toBeAdded)) {
            $code = $this->fileParser->parse($toBeAdded, file_get_contents($configFilePath));
            file_put_contents($configFilePath, "<?php\r\n" . $code);

            $this->output->line(count($toBeAdded) . ' service providers added');
        } else {
            $this->output->line('No service providers are added because none were selected or they were not available');
        }
    }

    protected function getServiceProviderList($list, $toBeAdded) {
        return array_diff($list, $toBeAdded);
    }
}