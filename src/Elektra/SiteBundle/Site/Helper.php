<?php

namespace Elektra\SiteBundle\Site;

use Elektra\CrudBundle\Crud\Crud;

abstract class Helper
{

    /**
     * @var Crud
     */
    protected static $crud;

    /**
     * @param Crud $crud
     */
    public static function setCrud(Crud $crud)
    {

        static::$crud = $crud;
    }

    /**
     * @return Crud
     * @throws \RuntimeException
     */
    protected static function getCrud()
    {

        if (static::$crud) {
            return static::$crud;
        }

        throw new \RuntimeException('No crud found');
    }

    /**
     * @param array $options1
     * @param array $options2
     *
     * @return array
     */
    public static function mergeOptions(array $options1, array $options2)
    {

        $merged = $options1;

        foreach ($options2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::mergeOptions($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function camelToUnderScore($string)
    {

        $newString = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));

        return $newString;
    }

    /**
     * @param string      $prefix
     * @param string      $suffix
     * @param string|null $specific
     *
     * @return string
     */
    public static function languageAlternate($prefix, $suffix, $specific = null)
    {

        $crud        = static::getCrud();
        $language    = $crud->getService('siteLanguage');
        $languageKey = $crud->getLanguageKey();

        $commonKey  = $prefix . '.generic.' . $suffix;
        $defaultKey = $prefix . '.' . $languageKey . '.' . $suffix;

        if ($specific !== null) {
            $specific    = static::camelToUnderScore($specific);
            $specificKey = $defaultKey . '.' . $specific;

            return $language->getAlternate($specificKey, $defaultKey, $commonKey);
        }

        return $language->getAlternate($defaultKey, $commonKey);
    }
}