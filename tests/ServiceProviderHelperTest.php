<?php
use Synga\InheritanceFinder\InheritanceFinderFactory;
use Synga\ServiceProviderHelper\ServiceProviderHelper;

class ServiceProviderHelperTest
{
    protected $serviceProviderHelper;

    protected function setUp() {
        $this->serviceProviderHelper = new ServiceProviderHelper(
            InheritanceFinderFactory::getInheritanceFinder(__DIR__ . '/cache/')
        );
    }

    public function testFindServiceProviders() {
    }
}