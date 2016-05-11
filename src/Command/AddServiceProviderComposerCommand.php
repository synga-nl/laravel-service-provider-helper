<?php
/**
 * Synga Inheritance Finder
 * @author      Roy Pouls
 * @copytright  2016 Roy Pouls / Synga (http://www.synga.nl)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/synga-nl/inheritance-finder
 */

namespace Synga\ServiceProviderHelper\Command;

use Composer\Script\Event;
use Synga\ConsoleAbstraction\Laravel\ComposerCommandAbstract;
use Synga\ServiceProviderHelper\ServiceProviderService;

/**
 * Class AddServiceProviderComposerCommand
 * @package Synga\ServiceProviderHelper\Command
 */
class AddServiceProviderComposerCommand extends ComposerCommandAbstract
{
    /**
     * @param Event $event
     */
    public static function addServiceProvider(Event $event) {
        self::bootLaravel($event);

        self::$consoleInteraction->getInput()->setOption('composer', true);

        $finder                    = self::$app->make('Synga\ServiceProviderHelper\ServiceProviderAdder');
        $serviceProviderFileParser = self::$app->make('Synga\ServiceProviderHelper\ServiceProviderFileParser');


        $serviceProviderService = new ServiceProviderService(self::$consoleInteraction, $finder, $serviceProviderFileParser);
        $serviceProviderService->handle();
    }
}
