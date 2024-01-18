<?php

namespace DrBlitz\Phone\ViewHelpers\Format;

use DOMDocument;
use libphonenumber\PhoneNumberUtil;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class PhoneViewHelper extends AbstractViewHelper
{

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'content', true);
        $this->registerArgument('region', 'string', 'country', true);
        $this->registerArgument('format', 'int', 'The desired format', true);
    }

    /**
     * @return mixed
     */
    public static function renderStatic(
        array                     $arguments,
        \Closure                  $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): mixed
    {
        $search = [];
        $replace = [];
        $text = $arguments['value'];
        $links = (new PhoneViewHelper)->findPhoneLinks($text);
        foreach ($links as $link) {
            $phoneNumber = self::getHrefFromAnchor($link);
            if ($phoneNumber === null) {
                continue;
            }
            $phoneUtil = PhoneNumberUtil::getInstance();
            try {
                $phoneProto = $phoneUtil->parse($phoneNumber, $arguments['region']);
                $linkText = $phoneUtil->format($phoneProto, \libphonenumber\PhoneNumberFormat::NATIONAL);
                $linkHref = $phoneUtil->format($phoneProto, \libphonenumber\PhoneNumberFormat::RFC3966);
                $newFullLink = self::updateLink(
                    $link,
                    $linkHref,
                    $linkText
                );
                $search[] = $link;
                $replace[] = $newFullLink;
            } catch (\libphonenumber\NumberParseException $e) {
                continue;
            }
        }
        return str_replace($search, $replace, $text);
    }

    protected static function updateLink($html, string $href, string $linkText)
    {
        $dom = new DOMDocument;
        $dom->loadHTML($html);

        $aTag = $dom->getElementsByTagName('a')->item(0);

        if ($aTag) {
            $aTag->setAttribute('href', $href);
            $aTag->nodeValue = $linkText;
            return $dom->saveHTML($aTag);
        }

        return $html;
    }


    protected static function getHrefFromAnchor($html)
    {
        $dom = new DOMDocument;
        $dom->loadHTML($html);

        $aTag = $dom->getElementsByTagName('a')->item(0);

        if ($aTag) {
            $href = $aTag->getAttribute('href');
            return str_replace('tel:', '', $href);
        }

        return null;
    }


    protected static function findPhoneLinks($text): array
    {
        $pattern = '/<a\s+[^>]*?href=["\']([^"\']*?tel:[^"\']*?)["\'][^>]*>(.*?)<\/a>/i';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $telLinks = array();
        foreach ($matches as $match) {
            $telLinks[] = trim($match[0]);
        }

        return $telLinks;
    }
}
