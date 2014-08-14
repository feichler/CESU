<?php

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

class LanguageChoice extends LanguageSimple
{

    protected $number;

    public function __construct($key = '', $number = null, $domain = null)
    {

        parent::__construct($key, $domain);

        if (is_int($number)) {
            $this->number = $number;
        } else {
            if ($number == 'plural') {
                $this->setPlural();
            } else {
                $this->setSingular();
            }
        }
    }

    public function setNumber($number)
    {

        $this->number = $number;
    }

    public function getNumber()
    {

        return $this->number;
    }

    public function setPlural()
    {

        $this->number = 2;
    }

    public function setSingular()
    {

        $this->number = 1;
    }

    public function getTranslation(TranslatorInterface $translator)
    {

        $translated = $translator->transChoice($this->key, $this->number, $this->replacements, $this->domain);

        if ($translated == $this->key) {
            return '';
        }

        return $translated;
    }
}