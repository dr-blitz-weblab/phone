<?php

namespace DrBlitz\Phone\ViewHelpers\Link;

use libphonenumber\PhoneNumberUtil;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PhoneViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'a';

    public function initializeArguments(): void
    {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('phoneNumber', 'string', 'phone number', true);
        $this->registerArgument('region', 'string', 'country', true);
        $this->registerArgument('format', 'int', 'The desired format', true);
    }

    public function render(): string
    {
        if ($this->arguments['phoneNumber'] === null) {
            return (string)$this->renderChildren();
        }
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = $phoneUtil->parse($this->arguments['phoneNumber'], $this->arguments['region']);
        $this->tag->addAttribute('href', $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::RFC3966));
        $this->tag->setContent($phoneUtil->format($phoneNumber, (int)$this->arguments['format']));
        $this->tag->forceClosingTag(true);
        return $this->tag->render();
    }
}
