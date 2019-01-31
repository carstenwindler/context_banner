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
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['context_banner']
	= 'EXT:context_banner/Classes/Renderer.php:&CarstenWindler\\ContextBanner\\Renderer->contentPostProcOutputHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPreProcess']['context_banner']
	= 'EXT:context_banner/Classes/Renderer.php:&CarstenWindler\\ContextBanner\\Renderer->backendRenderPreProcessHook';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['renderPostProcess']['context_banner']
		= 'EXT:context_banner/Classes/Renderer.php:&CarstenWindler\\ContextBanner\\Renderer->backendRenderPostProcessHook';
