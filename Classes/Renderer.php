<?php

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Context Banner Renderer
 *
 * @author Carsten Windler <carsten@carstenwindler.de>
 */
class Renderer
{
    /**
     * Extension key
     * @var string
     */
    private $extKey = 'context_banner';

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

    public function __construct()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey])) {
            $this->setConf(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]));
        }

        $this->contextName = (string) GeneralUtility::getApplicationContext();
    }

    public function setConf(array $conf): self
    {
        $this->conf = array_merge($this->conf, $conf);

        return $this;
    }

    private function shouldHideFrontendBanner(): bool
    {
        return (isset($this->conf['hideFrontendBanner']) && $this->conf['hideFrontendBanner'] === 1);
    }

    private function shouldFrontendBannerBeShownOnProduction(): bool
    {
        return (isset($this->conf['showFrontendBannerOnProduction']) &&
            $this->conf['showFrontendBannerOnProduction'] === 1);
    }

    private function shouldHideBackendBanner(): bool
    {
        return (isset($this->conf['hideBackendBanner']) && $this->conf['hideBackendBanner'] === 1);
    }

    public function isFrontendBannerShown(): bool
    {
        if ($this->contextName === 'Production' && !$this->shouldFrontendBannerBeShownOnProduction()) {
            return false;
        }

        return !$this->shouldHideFrontendBanner();
    }

    public function isBackendBannerShown(): bool
    {
        return !$this->shouldHideBackendBanner();
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
    public function backendRenderPreProcessHook(array &$params)
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = $this->getBannerText();
    }

    /**
     * Hooked into backendRenderPreProcess
     */
    public function backendRenderPostProcessHook(array &$params)
    {
        if (!$this->isBackendBannerShown()) {
            return;
        }

        $outputArray = array();
        preg_match('/<body[^<]*>/', $params['content'], $outputArray);

        // We expect the first occurance of <body> to be the correct one
        // there should be only one anyway
        $bodyTag = array_shift($outputArray);

        $params['content'] = str_replace($bodyTag, $bodyTag . $this->renderBackendBanner(), $params['content']);
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

    private function renderBanner(): string
    {
        return '<div class="contextbanner" style="' . $this->getInlineCss() . '">' . $this->getBannerText() . '</div>';
    }

    public function renderBackendBanner(): string
    {
        // right now, both banners are the same
        return $this->renderBanner();
    }

    public function renderFrontendBanner(): string
    {
        // right now, both banners are the same
        return $this->renderBanner();
    }
}
