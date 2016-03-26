<?php
namespace Synga\ServiceProviderHelper\Parser\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Class ServiceProviderVisitor
 * @package Synga\ServiceProviderHelper\Parser\Visitor
 */
class ServiceProviderVisitor extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * @param Node $node
     * @return false|null|Node|\PhpParser\Node[]|void
     */
    public function leaveNode(Node $node) {
        if ($node instanceof \PhpParser\Node\Expr\ArrayItem) {
            if (is_object($node->key)) {
                if ($node->key->value == "providers") {
                    if (is_array($node->value->items)) {
                        foreach ($this->serviceProviders as $serviceProvider) {
                            $arrayItem            = $this->addServiceProvider($serviceProvider);
                            $node->value->items[] = $arrayItem;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $serviceProvider
     * @return Node\Expr\ArrayItem
     */
    protected function addServiceProvider($serviceProvider) {
        $fullQualified   = new Node\Name\FullyQualified($serviceProvider);
        $classConstFetch = new Node\Expr\ClassConstFetch($fullQualified, 'class');

        return new Node\Expr\ArrayItem($classConstFetch);
    }

    /**
     * @param $serviceProvider
     */
    public function setServiceProvider($serviceProvider) {
        if (is_array($serviceProvider)) {
            $this->serviceProviders = array_merge($this->serviceProviders, $serviceProvider);
        } else {
            $this->serviceProviders[] = $serviceProvider;
        }
    }
}