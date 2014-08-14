<?php

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

abstract class Language
{

    protected $domain;

    protected $fallback;

    public function __construct($domain = null)
    {

        $this->domain   = $domain;
        $this->fallback = true;
    }

    public function setFallback()
    {

        $this->fallback = true;
    }

    public function resetFallback()
    {

        $this->fallback = false;
    }

    public function fallback()
    {

        return $this->fallback;
    }

    public function setDomain($domain)
    {

        $this->domain = $domain;
    }

    public function getDomain()
    {

        return $this->domain;
    }

    public abstract function getTranslation(TranslatorInterface $translator);
}