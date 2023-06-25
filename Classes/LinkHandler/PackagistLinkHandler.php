<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\Linkhandler\LinkHandler;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Controller\AbstractLinkBrowserController;
use TYPO3\CMS\Backend\LinkHandler\LinkHandlerInterface;
use TYPO3\CMS\Backend\LinkHandler\LinkHandlerViewProviderInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewInterface;

class PackagistLinkHandler implements LinkHandlerInterface, LinkHandlerViewProviderInterface
{
    /**
     * Available additional link attributes
     */
    protected array $linkAttributes = ['class'];

    /**
     * Parts of the current link
     */
    protected array $linkParts = [];

    protected PageRenderer $pageRenderer;

    protected ViewInterface $view;

    public function initialize(AbstractLinkBrowserController $linkBrowser, $identifier, array $configuration)
    {
        $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
    }

    public function getLinkAttributes(): array
    {
        return $this->linkAttributes;
    }

    /**
     * @param string[] $fieldDefinitions Array of link attribute field definitions
     */
    public function modifyLinkAttributes(array $fieldDefinitions): array
    {
        return $fieldDefinitions;
    }

    /**
     * Checks if this is the handler for the given link
     *
     * The handler may store this information locally for later usage.
     *
     * @param array $linkParts Link parts as returned from TypoLinkCodecService
     */
    public function canHandleLink(array $linkParts): bool
    {
        if (isset($linkParts['url']['vendor'], $linkParts['url']['package'])) {
            $this->linkParts = $linkParts;

            return true;
        }
    }

    /**
     * Format the current link for HTML output
     */
    public function formatCurrentUrl(): string
    {
        return '<h1>Narf</h1>';
    }

    /**
     * Render the link handler. Ideally this modifies the view, but it can also render content directly.
     */
    public function render(ServerRequestInterface $request): string
    {
        $this->pageRenderer->loadJavaScriptModule('@stefanfroemken/linkhandler/packagist-link-handler.js');
        $this->view->assign('vendor', $this->linkParts['url']['vendor'] ?: '');
        $this->view->assign('package', $this->linkParts['url']['package'] ?: '');

        return $this->view->render('LinkBrowser/Packagist');
    }

    /**
     * Return TRUE if the handler supports to update a link.
     *
     * This is useful for file or page links, when only attributes are changed.
     */
    public function isUpdateSupported(): bool
    {
        return true;
    }

    public function getBodyTagAttributes(): array
    {
        return [];
    }

    public function createView(BackendViewFactory $backendViewFactory, ServerRequestInterface $request): ViewInterface
    {
        return $backendViewFactory->create($request, ['stefanfroemken/linkhandler']);
    }

    public function setView(ViewInterface $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): ViewInterface
    {
        return $this->view;
    }
}
