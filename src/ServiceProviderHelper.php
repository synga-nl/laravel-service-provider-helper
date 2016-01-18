<?php
namespace Synga\ServiceProviderHelper;

use Synga\InheritanceFinder\InheritanceFinder;

/**
 * Class ServiceProviderHelper
 * @package Synga\ServiceProviderHelper
 */
class ServiceProviderHelper
{
    /**
     * @var InheritanceFinder
     */
    private $inheritanceFinder;

    /**
     * ServiceProviderHelper constructor.
     * @param InheritanceFinder $inheritanceFinder
     */
    public function __construct(InheritanceFinder $inheritanceFinder) {
        $this->inheritanceFinder = $inheritanceFinder;
    }

    /**
     *
     */
    public function findDifferences(){

    }
}