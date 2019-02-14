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

    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][] = \CarstenWindler\ContextBanner\Backend\ToolbarItems\ApplicationContextToolbarItem::class;
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['context_banner']
    = 'EXT:context_banner/Classes/Main.php:&CarstenWindler\\ContextBanner\\Main->contentPostProcOutputHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['context_banner']
    = 'EXT:context_banner/Classes/Main.php:&CarstenWindler\\ContextBanner\\Main->backendRenderPreProcessHook';
