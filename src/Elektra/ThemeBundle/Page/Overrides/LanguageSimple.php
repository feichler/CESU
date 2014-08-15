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
 * Class LanguageSimple
 *
 * @package Elektra\ThemeBundle\Page\Overrides
 *
 * @version 0.1-dev
 */
class LanguageSimple extends Language
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $replacements;

    /**
     * @param string      $key
     * @param string|null $domain
     */
    public function __construct($key = '', $domain = null)
    {

        parent::__construct($domain);
        $this->key          = $key;
        $this->replacements = array();
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {

        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {

        return $this->key;
    }

    /**
     * @param string          $placeholder
     * @param string|Language $replacement
     */
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

    /**
     * @return array
     */
    public function getReplacements()
    {

        return $this->replacements;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(TranslatorInterface $translator)
    {

        // check if any replacement is a Language Object itself
        foreach ($this->replacements as $placeholder => &$replacement) {
            if ($replacement instanceof Language) {
                $replacement = $replacement->getTranslation($translator);
            }
        }

        $translated = $translator->trans($this->key, $this->replacements, $this->domain);

        if ($translated == $this->key) {
            return '';
        }

        return $translated;
    }
}