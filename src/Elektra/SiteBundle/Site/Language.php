<?php


namespace Elektra\SiteBundle\Site;

use Symfony\Component\Translation\TranslatorInterface;

class Language
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var array
     */
    protected $strings;

    /**
     * @var array
     */
    protected $previous;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {

        $this->translator = $translator;
        $this->strings    = array();
        $this->previous   = array();
        $this->setDefaults();
    }

    /**
     *
     */
    private function setDefaults()
    {

        $this->add('user.login', 'user.login');
        $this->add('user.logout', 'user.logout');
        $this->add('copyright', 'generic.copyright');
        $this->add('brand', 'generic.brand');
        $this->add('title', 'pages.generic.title');
        $this->add('title_prefix', 'pages.generic.title_prefix');
        $this->add('title_suffix', 'pages.generic.title_suffix');
        $this->add('heading', 'pages.generic.heading');
        $this->add('section', 'pages.generic.section');
    }

    /**
     * @param string $key
     * @param string $identifier
     * @param array  $parameters
     * @param bool   $override
     */
    public function add($key, $identifier, $parameters = array(), $override = false)
    {

        $this->set($key, $identifier, null, $parameters, $override);
    }

    /**
     * @param string $key
     * @param string $identifier
     * @param array  $parameters
     */
    public function override($key, $identifier, $parameters = array())
    {

        $this->add($key, $identifier, $parameters, true);
    }

    /**
     * @param string $key
     * @param string $identifier
     * @param int    $number
     * @param array  $parameters
     * @param bool   $override
     */
    public function addChoice($key, $identifier, $number, $parameters = array(), $override = false)
    {

        $this->set($key, $identifier, $number, $parameters, $override);
    }

    /**
     * @param string $key
     * @param string $identifier
     * @param int    $number
     * @param array  $parameters
     */
    public function overrideChoice($key, $identifier, $number, $parameters = array())
    {

        $this->addChoice($key, $identifier, $number, $parameters, true);
    }

    /**
     * @param string $key
     * @param string $identifier
     * @param int    $number
     * @param array  $parameters
     * @param bool   $override
     */
    public function set($key, $identifier, $number = null, $parameters = array(), $override = false)
    {

        /*
         * NOTE: Logic for setting a value: (1) $key is not found || (2) $override == true
         */

        if ($number === null) {
            $translated = $this->translator->trans($identifier, $this->prepareParameters($parameters), 'ElektraSite');
        } else {
            $translated = $this->translator->transChoice($identifier, $number, $this->prepareParameters($parameters), 'ElektraSite');
        }

        if ($translated == $identifier && $identifier != '') { // $identifier != '' in order to "reset" translations to empty
            // URGENT: show ~~ only in dev environment
            $translated = '~~ ' . $translated . ' ~~';
        }

        if (!array_key_exists($key, $this->strings)) {
            $this->strings[$key] = $translated;
        } else if ($override) {
            $this->previous[$key] = $this->strings[$key];
            $this->strings[$key]  = $translated;
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isTranslated($key)
    {

        if (array_key_exists($key, $this->strings)) {
            $translated = $this->strings[$key];
            if ($translated == '') { // allow empty translations to omit displaying of some text
                return true;
            }
            if ($translated[0] == '~') {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $key
     */
    public function restore($key)
    {

        if (array_key_exists($key, $this->previous)) {
            $this->strings[$key] = $this->previous[$key];
            unset($this->previous[$key]);
        }
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function prepareParameters($parameters)
    {

        if (empty($parameters)) {
            return $parameters;
        }

        $newParameters = array();

        foreach ($parameters as $key => $value) {

            if ($key[0] != '%') {
                $key = '%' . $key;
            }
            if (substr($key, -1) != '%') {
                $key = $key . '%';
            }

            $newParameters[$key] = $value;
        }

        return $newParameters;
    }

    /**
     * @param string $key
     * @param bool   $required
     *
     * @return string
     */
    private function getString($key, $required)
    {

        if (array_key_exists($key, $this->strings)) {
            return $this->strings[$key];
        } else if ($required) {
            $this->add($key, $key);

            return $this->getString($key, false);
        }

        return '';
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get($key)
    {

        return $this->getString($key, false);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getRequired($key)
    {

        return $this->getString($key, true);
    }

    public function getAlternate($langKey, $alternateKey, $alternateKey2 = null)
    {

        $this->add($langKey, $langKey);
        if ($this->isTranslated($langKey)) {
            return $this->get($langKey);
        }

        if ($alternateKey2 !== null) {
            unset($this->strings[$langKey]);
            $this->add($langKey, $alternateKey);
            if ($this->isTranslated($langKey)) {
                return $this->get($langKey);
            }

            return $this->getRequired($alternateKey2);
        }

        return $this->getRequired($alternateKey);
    }
}