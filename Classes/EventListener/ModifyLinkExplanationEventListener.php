<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Linkhandler\EventListener;

use TYPO3\CMS\Backend\Form\Event\ModifyLinkExplanationEvent;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;

class ModifyLinkExplanationEventListener
{
    protected IconFactory $iconFactory;

    public function __construct(IconFactory $iconFactory)
    {
        $this->iconFactory = $iconFactory;
    }

    public function __invoke(ModifyLinkExplanationEvent $event): void
    {
        if (($event->getLinkData()['type'] ?? false) === 'packagist') {
            $event->setLinkExplanationValue(
                'text',
                sprintf(
                    'https://packagist.org/packages/%s/%s',
                    $event->getLinkData()['vendor'] ?: 'vendor',
                    $event->getLinkData()['package'] ?: 'package',
                )
            );

            $event->setLinkExplanationValue(
                'icon',
                $this->iconFactory->getIcon('service-packagist', Icon::SIZE_SMALL)->render()
            );
        }
    }
}
