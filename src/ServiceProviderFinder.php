<?php
/**
 * Synga Inheritance Finder
 * @author      Roy Pouls
 * @copytright  2016 Roy Pouls / Synga (http://www.synga.nl)
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @link        https://github.com/synga-nl/inheritance-finder
 */

namespace Synga\ServiceProviderHelper;
use Synga\InheritanceFinder\PhpClass;

/**
 * Class ServiceProviderFinder
 * @package Synga\ServiceProviderHelper
 */
class ServiceProviderFinder
{
    /**
     * Compares service providers found in files with the config service providers
     *
     * @return array
     */
    public function find() {
        $availableProviders = $this->inheritanceFinder->findExtends('\Illuminate\Support\ServiceProvider');

        $usedProviders = \Config::get('app.providers');

        return $this->assortArrays($availableProviders, $usedProviders);
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

        foreach(['firstArray' => $firstArray, 'secondArray' =
            if(is_a($arg[0], PhpClass::class)){
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