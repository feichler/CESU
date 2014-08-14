<?php

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

class LanguageSimple extends Language
{

    protected $key;

    protected $replacements;

    public function __construct($key = '', $domain = null)
    {

        parent::__construct($domain);
        $this->key          = $key;
        $this->replacements = array();
    }

    public function setKey($key)
    {

        $this->key = $key;
    }

    public function getKey()
    {

        return $this->key;
    }

    public function addReplacement($placeholder, $replacement)
    {

        if ($placeholder[0] != '%') {
            $placeholder = '%' . $placeholder;
        }
        if ($placeholder[strlen($placeholder) - 1] != '%') {
            $placeholder .= '%';
        }

        $this->replacements[$placeholder] = $replacement;
    }

    public function getReplacements()
    {

        return $this->replacements;
    }

    public function getTranslation(TranslatorInterface $translator)
    {

        $translated = $translator->trans($this->key, $this->replacements, $this->domain);

        if ($translated == $this->key) {
            return '';
        }

        return $translated;
    }
}