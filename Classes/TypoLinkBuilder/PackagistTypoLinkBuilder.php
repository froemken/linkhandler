<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Linkhandler\TypoLinkBuilder;

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Typolink\AbstractTypolinkBuilder;
use TYPO3\CMS\Frontend\Typolink\LinkResult;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

class PackagistTypoLinkBuilder extends AbstractTypolinkBuilder
{
    public function build(array &$linkDetails, string $linkText, string $target, array $conf): LinkResultInterface
    {
        $uri = sprintf(
            'https://packagist.org/packages/%s/%s/stats.json',
            $linkDetails['vendor'],
            $linkDetails['package']
        );
        $response = \json_decode(file_get_contents($uri));
        $linkText .= ' <span>(Total Downloads: ' . $response->downloads->total . ')</span>';

        $uri = sprintf(
            'https://packagist.org/packages/%s/%s',
            $linkDetails['vendor'],
            $linkDetails['package']
        );

        return (new LinkResult($linkDetails['type'], $uri))
            ->withTarget($target)
            ->withLinkConfiguration($conf)
            ->withLinkText($linkText);
    }

    private function getPackageManager(): PackageManager
    {
        return GeneralUtility::makeInstance(PackageManager::class);
    }
}
