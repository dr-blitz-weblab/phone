<?php

namespace DrBlitz\Phone\ViewHelpers\Link;

use libphonenumber\PhoneNumberUtil;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;


final class PhoneViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'a';

    public function initializeArguments(): void
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('phoneNumber', 'string', 'phone number', true);
        $this->registerArgument('region', 'string', 'country', false);
        $this->registerArgument('format', 'int', 'The desired format', true);
    }

    public function render(): string
    {
        if ($this->arguments['phoneNumber'] === null) {
            return (string)$this->renderChildren();
        }
        if ($this->arguments['region'] === null) {
            $context = GeneralUtility::makeInstance(Context::class);
            /** @var \TYPO3\CMS\Core\Site\Entity\Site */
            $site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
            $langId = $context->getPropertyFromAspect('language', 'id');
            /** @var \TYPO3\CMS\Core\Site\Entity\SiteLanguage */
            $language = $site->getLanguageById($langId);
            $region = $language->getTwoLetterIsoCode();
        } else {
            $region = $this->arguments['region'];
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = $phoneUtil->parse($this->arguments['phoneNumber'], $region);
        $this->tag->addAttribute('href', $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::RFC3966));
        $this->tag->setContent($phoneUtil->format($phoneNumber, (int)$this->arguments['format']));
        $this->tag->forceClosingTag(true);
        return $this->tag->render();
    }

    /**
     * @return int
     */
    protected function getLanguageIdentifier(): int
    {
        /** @var SiteLanguage $language */
        $language = $this->request->getAttribute('language');
        return $language->getLanguageId();
    }
}
