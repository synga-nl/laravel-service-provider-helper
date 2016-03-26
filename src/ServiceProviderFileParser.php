<?php
/**
 * Synga Inheritance Finder
 * @author      Roy Pouls
 * @copytright  2016 Roy Pouls / Synga (http://www.synga.nl)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/synga-nl/inheritance-finder
 */

namespace Synga\ServiceProviderHelper;


use PhpParser\NodeTraverser;
use PhpParser\Parser;
use Synga\ServiceProviderHelper\Parser\PrettyPrinter\LaravelPrettyPrinter;
use Synga\ServiceProviderHelper\Parser\Visitor\ServiceProviderVisitor;

/**
 * Class ServiceProviderFileParser
 * @package Synga\ServiceProviderHelper
 */
class ServiceProviderFileParser
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * ServiceProviderFileParser constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser) {
        $this->parser = $parser;
    }

    /**
     * @param $toBeAdded
     * @param $configPath
     * @return string
     */
    public function parse($toBeAdded, $initalCode){
        $nodes  = $this->parser->parse($initalCode);

        $traverser = new NodeTraverser();

        $addServiceProviderVisitor = new ServiceProviderVisitor();
        $addServiceProviderVisitor->setServiceProvider($toBeAdded);
        $traverser->addVisitor($addServiceProviderVisitor);

        $parsedNodes = $traverser->traverse($nodes);

        $prettyPrinter = new LaravelPrettyPrinter(['shortArraySyntax' => true]);

        return $prettyPrinter->prettyPrint($parsedNodes);
    }
}