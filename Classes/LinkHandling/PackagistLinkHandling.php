<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Linkhandler\LinkHandling;

use TYPO3\CMS\Core\LinkHandling\LinkHandlingInterface;

class PackagistLinkHandling implements LinkHandlingInterface
{
    /**
     * Returns a string interpretation of the link href query from objects, something like
     *
     *  - t3://page?uid=23&my=value#cool
     *  - https://www.typo3.org/
     *  - t3://file?uid=13
     *  - t3://folder?storage=2&identifier=/my/folder/
     *  - mailto:mac@safe.com
     *
     * array of data -> string
     *
     * @param array $parameters
     */
    public function asString(array $parameters): string
    {
        return 't3://packagist?vendor=stefanfroemken&package=mysqlreport&info=stats';
    }

    /**
     * Returns an array with data interpretation of the link href from parsed query parameters of urn
     * representation.
     *
     * array of strings -> array of data
     *
     * @param array $data
     */
    public function resolveHandlerData(array $data): array
    {
        if (empty($data['vendor']) || empty($data['package'])) {
            throw new \InvalidArgumentException('The Packagist LinkHandling needs at least vendor and packackage', 1687642136);
        }

        return $data;
    }
}
