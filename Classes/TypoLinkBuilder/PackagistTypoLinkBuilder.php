<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Linkhandler\TypoLinkBuilder;

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Typolink\AbstractTypolinkBuilder;
use TYPO3\CMS\Frontend\Typolink\LinkResult;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

class PackagistTypoLinkBuilder extends AbstractTypolinkBuilder
{
    public function build(array &$linkDetails, string $linkText, string $target, array $conf): LinkResultInterface
    {
        try {
            if (in_array($linkDetails['info'] ?? '', ['total', 'monthly', 'daily'])) {
                $linkText .= sprintf(
                    ' <span>(%s downloads: %d)</span>',
                    ucfirst($linkDetails['info']),
                    $this->getDownloads($linkDetails, $linkDetails['info'])
                );
            }
            if (($linkDetails['info'] ?? '') === 'favers') {
                $linkText .= sprintf(
                    ' <span>(Favs: %d)</span>',
                    $this->getFavorites($linkDetails)
                );
            }
        } catch (\Exception $exception) {
            // on exception keep $linkText untouched
        }

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

    private function getDownloads(array $linkDetails, string $interval): int
    {
        $uri = sprintf(
            'https://packagist.org/packages/%s/%s/stats.json',
            $linkDetails['vendor'],
            $linkDetails['package']
        );

        $response = $this->getRequestFactory()->request(
            $uri,
            'GET',
            [
                'connect_timeout' => 2,
                'read_timeout' => 1,
                'timeout' => 2,
            ]
        );

        if ($response->getStatusCode() === 200) {
            $info = \json_decode((string)$response->getBody(), true);
        }

        return $info['downloads'][$interval] ?? 0;
    }

    private function getFavorites(array $linkDetails): int
    {
        $uri = sprintf(
            'https://packagist.org/packages/%s/%s.json',
            $linkDetails['vendor'],
            $linkDetails['package']
        );

        $response = $this->getRequestFactory()->request(
            $uri,
            'GET',
            [
                'connect_timeout' => 2,
                'read_timeout' => 1,
                'timeout' => 2,
            ]
        );

        if ($response->getStatusCode() === 200) {
            $info = \json_decode((string)$response->getBody(), true);
        }

        return $info['package']['favers'] ?? 0;
    }

    private function getRequestFactory(): RequestFactory
    {
        return GeneralUtility::makeInstance(RequestFactory::class);
    }
}
