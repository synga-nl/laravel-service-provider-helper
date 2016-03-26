<?php
/**
 * Synga Inheritance Finder
 * @author      Roy Pouls
 * @copytright  2016 Roy Pouls / Synga (http://www.synga.nl)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/synga-nl/inheritance-finder
 */

namespace Synga\ServiceProviderHelper;

use Synga\InheritanceFinder\InheritanceFinderInterface;
use Synga\InheritanceFinder\PhpClass;

/**
 * Class ServiceProviderAdder
 * @package Synga\ServiceProviderHelper
 */
class ServiceProviderAdder
{
    /**
     * @var InheritanceFinderInterface
     */
    protected $inheritanceFinder;

    public function __construct(InheritanceFinderInterface $inheritanceFinder) {
        $this->inheritanceFinder = $inheritanceFinder;
    }

    /**
     * Compares service providers found in files with the config service providers
     *
     * @return array
     */
    public function find() {
        $availableProviders = $this->inheritanceFinder->findExtends('\Illuminate\Support\ServiceProvider');

        $usedProviders = \Config::get('app.providers');

        $assertedServiceProviders = $this->assortArrays($availableProviders, $usedProviders);

        $serviceProviderPath      = storage_path('service_provider');
        $serviceProviderCachePath = $serviceProviderPath . '/service_provider.cache';

        if (!file_exists($serviceProviderPath) || !file_exists($serviceProviderCachePath)) {
            mkdir($serviceProviderPath);
            file_put_contents($serviceProviderCachePath, serialize([]));
        }

        $cache = unserialize(file_get_contents($serviceProviderCachePath));

        if (!empty($cache['ignore'])) {
            foreach ($assertedServiceProviders['first_diff_second'] as $key => $serviceProvider) {
                if (in_array($serviceProvider, $cache['ignore'])) {
                    unset($assertedServiceProviders['first_diff_second'][$key]);
                }
            }
        }

        return $assertedServiceProviders;
    }

    public function findActiveServiceProviders() {
        return \Config::get('app.providers');
    }

    public function writeAppFile($toBeAddedServiceProviders) {

    }

    /**
     * Gives the diffs for the two array and the combined version
     *
     * @param $firstArray
     * @param $secondArray
     * @return array
     */
    protected function assortArrays($firstArray, $secondArray) {
        if (empty($firstArray) && empty($secondArray)) {
            return [
                'first_diff_second' => [],
                'second_diff_first' => [],
                'combined'          => []
            ];
        }

        foreach (['firstArray' => $firstArray, 'secondArray' => $secondArray] as $varName => $arg) {
            if (is_a($arg[0], PhpClass::class)) {
                $$varName = $this->getFullQualifiedNamespaceArray($arg);
            }
        }


        return [
            'first_diff_second' => array_diff($firstArray, $secondArray),
            'second_diff_first' => array_diff($secondArray, $firstArray),
            'combined'          => array_unique(array_merge($firstArray, $secondArray))
        ];

    }

    /**
     * @param PhpClass[] $classes
     * @return array
     */
    protected function getFullQualifiedNamespaceArray($classes) {
        $result = [];

        foreach ($classes as $class) {
            $result[] = $class->getFullQualifiedNamespace();
        }

        return $result;
    }
}