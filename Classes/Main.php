<?php declare(strict_types=1);

namespace CarstenWindler\ContextBanner;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Context Banner Renderer
 *
 * @author Carsten Windler <carsten@carstenwindler.de>
 */
class Main
{
    const EXT_KEY = 'context_banner';

    /**
     * Extension key
     * @var string
     */
    private $extKey = self::EXT_KEY;

    /**
     * Extension configuration
     * @var array
     */
    private $conf = [];

    /**
     * Application context name
     * @var string
     */
    private $contextName;

    /**
     * @var array
     */
    private $toolbarIconBackgroundColors = [
        'Development' => '#00FF00',
        'Testing' => '#FFFF00',
        'Production' => '#FF0000'
    ];

    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey])) {
            $this->setConf(
                unserialize(
                    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey],
                    [ 'allowed_classes' => false ]
                )
            );
        }

        $this->contextName = (string) GeneralUtility::getApplicationContext();
    }

    public function setConf(array $conf): self
    {
        $this->conf = array_merge($this->conf, $conf);

        return $this;
    }

    private function shouldFrontendBannerBeShownOnProduction(): bool
    {
        return (isset($this->conf['showBannerOnProduction']) &&
            $this->conf['showBannerOnProduction'] === 1);
    }

    public function isFrontendBannerShown(): bool
    {
        if ($this->contextName === 'Production' && !$this->shouldFrontendBannerBeShownOnProduction()) {
            return false;
        }

        return true;
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    public function isToolbarItemShown(): bool
    {
        return $this->getBackendUser()->isAdmin();
    }

    /**
     * Hooked into contentPostProc_output
     */
    public function contentPostProcOutputHook(array &$params)
    {
        if (!$this->isFrontendBannerShown()) {
            return;
        }

        $feobj = &$params['pObj'];
        $outputArray = array();
        preg_match('/<body[^<]*>/', $feobj->content, $outputArray);

        // We expect the first occurence of <body> to be the correct one
        // there should be only one anyway
        $bodyTag = array_shift($outputArray);

        $feobj->content = str_replace($bodyTag, $bodyTag . $this->renderFrontendBanner(), $feobj->content);

        $outputArray = array();
        preg_match('/<title[^<]*>/', $feobj->content, $outputArray);

        // We expect the first occurence of <title> to be the correct one
        // there should be only one anyway
        $titleTag = array_shift($outputArray);

        $feobj->content = str_replace($titleTag, $titleTag . $this->getBannerText() . ' - ', $feobj->content);
    }

    /**
     * Hooked into backendRenderPreProcess
     */
    public function backendRenderPreProcessHook()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = $this->getBannerText();
    }

    private function getInlineCss(): string
    {
        $style = '';

        if ($this->conf['bannerStyle'] === 'auto') {
            $styleConfigName = 'bannerCss' . $this->contextName;

            if (!isset($this->conf[$styleConfigName])) {
                // should only happen if a new application context is introduced
                return '';
            }

            $style = $this->conf[$styleConfigName];
        }

        if ($this->conf['bannerStyle'] === 'custom' && isset($this->conf['bannerCssCustom'])) {
            $style = $this->conf['bannerCssCustom'];
        }

        return $style;
    }

    private function getBannerText(): string
    {
        $siteName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'];

        return str_replace(
            [ '###sitename###', '###context###' ],
            [ $siteName, $this->contextName ],
            $this->conf['bannerTemplate']
        );
    }

    public function renderFrontendBanner(): string
    {
        return '<div class="contextbanner" style="' . $this->getInlineCss() . '">' . $this->getBannerText() . '</div>';
    }

    public function renderToolbarItem(): string
    {
        $backgroundColor  = $this->toolbarIconBackgroundColors[$this->contextName] ?? '';

        return '<span style="color: #000000; background-color: ' . $backgroundColor .
            '" class="toolbar-item-link" title="Application context">' . strtoupper($this->contextName) . '</span>';
    }
}
