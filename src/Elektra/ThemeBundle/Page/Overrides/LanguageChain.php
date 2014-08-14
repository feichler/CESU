<?php

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

class LanguageChain extends Language
{

    private $chain;

    public function __construct()
    {

        parent::__construct();
        $this->chain = array();
    }

    public function addOption(Language $option)
    {

        $this->chain[] = $option;
    }

    public function getChain()
    {

        return $this->chain;
    }

    public function getTranslation(TranslatorInterface $translator)
    {

        foreach($this->chain as $i => $option) {
            if($option instanceof Language) {
                $translated = $option->getTranslation($translator);
                $key = $option->getKey();
                if($key != $translated) {
                    return $translated;
                }
            }
        }

        return '';
    }
}