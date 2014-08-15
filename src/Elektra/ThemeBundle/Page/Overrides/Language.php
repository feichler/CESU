<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Language
 *
 * @package Elektra\ThemeBundle\Page\Overrides
 *
 * @version 0.1-dev
 */
abstract class Language
{

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $fallback;

    /**
     * @param string|null $domain
     */
    public function __construct($domain = null)
    {

        $this->domain   = $domain;
        $this->fallback = true;
    }

    /**
     *
     */
    public function setFallback()
    {

        $this->fallback = true;
    }

    /**
     *
     */
    public function resetFallback()
    {

        $this->fallback = false;
    }

    /**
     * @return bool
     */
    public function fallback()
    {

        return $this->fallback;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {

        $this->domain = $domain;
    }

    /**
     * @return null|string
     */
    public function getDomain()
    {

        return $this->domain;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @return string
     */
    public abstract function getTranslation(TranslatorInterface $translator);
}