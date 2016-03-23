<?php
namespace Synga\ServiceProviderHelper\Parser\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Class AddServiceProviderVisitor
 * @package Synga\ServiceProviderHelper\Parser\Visitor
 */
class AddServiceProviderVisitor extends NodeVisitorAbstract
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
                        $node->value->items[] = $this->addServiceProvider('Synga\FrameworkKernelHelper\Laravel\FrameworkKernelHelperServiceProvider');
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
        $this->serviceProviders[] = $serviceProvider;
    }
}