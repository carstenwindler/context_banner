<?php

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Carstenwindler.' . $_EXTKEY,
        'tools',
        'context_banner'
    );

    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][]
        = \CarstenWindler\ContextBanner\Backend\ToolbarItems\ApplicationContextToolbarItem::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['context_banner']
        = \CarstenWindler\ContextBanner\Main::class . '->backendRenderPreProcessHook';
}
