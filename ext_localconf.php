<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['packagist']
        = \StefanFroemken\Linkhandler\LinkHandling\PackagistLinkHandling::class;
    $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['packagist']
        = \StefanFroemken\Linkhandler\TypoLinkBuilder\PackagistTypoLinkBuilder::class;
});
