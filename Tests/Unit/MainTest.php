<?php

use CarstenWindler\ContextBanner\Main;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * @covers \CarstenWindler\ContextBanner\Main
 */
class MainTest extends UnitTestCase
{
    /**
     * @var Main
     */
    private $sut;

    /**
     * @var array
     */
    private $defaultConfiguration;

    protected function setUp()
    {
        // This way we don't need to start the whole FE rendering process
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->content = '<body><div id="someotherdiv"></div></body>';

        $this->defaultConfiguration = [
            'pageTitleTemplate' => '###context### - ###sitename###',
            'bannerTemplate' => '###context###',
            'bannerStyle' => 'auto',
            'bannerCssCustom' => 'custom',
            'bannerCssDevelopment' => 'development',
            'bannerCssTesting' => 'testing',
            'bannerCssProduction' => 'production',
            'showBannerOnProduction' => '0',
        ];
    }

    protected function tearDown()
    {
        unset($this->sut);
        unset($this->testingFramework);
        unset($this->defaultConfiguration);
    }

    private function prepareDefaultSut()
    {
        $this->sut = new Main();
        $this->sut->setConf($this->defaultConfiguration);
    }

    private function setApplicationContext($context)
    {
        // GeneralUtility does not let us change ApplicationContext after init (which makes sense)
        // however for testing we need it, and resetApplicationContext() seem to be removed, so....
        $applicationContextProperty = new ReflectionProperty(\TYPO3\CMS\Core\Utility\GeneralUtility::class, 'applicationContext');
        $applicationContextProperty->setAccessible(true);
        $applicationContextProperty->setValue(null);

        $applicationContext = new \TYPO3\CMS\Core\Core\ApplicationContext($context);

        \TYPO3\CMS\Core\Utility\GeneralUtility::presetApplicationContext($applicationContext);
    }

    /**
     * @test
     */
    public function showFrontendBannerOnProductionWhenActivated()
    {
        $this->setApplicationContext('Production');

        $this->prepareDefaultSut();
        $this->sut->setConf(array_merge(
            $this->defaultConfiguration,
            array('showBannerOnProduction' => 1)
        ));

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->sut->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body><div class="contextbanner" style="production">Production</div><div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content,
            'Frontend Banner was shown although in Production context'
        );
    }

    /**
     * @test
     * @dataProvider frontendBannerProvider
     */
    public function showFrontendBanner($context, $bannerHtml)
    {
        $this->setApplicationContext($context);

        $this->prepareDefaultSut();

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->sut->contentPostProcOutputHook($params);

        $this->assertEquals(
            $bannerHtml,
            $GLOBALS['TSFE']->content
        );
    }

    public function frontendBannerProvider()
    {
        return [
            [ 'Development', '<body><div class="contextbanner" style="development">Development</div><div id="someotherdiv"></div></body>' ],
            [ 'Testing', '<body><div class="contextbanner" style="testing">Testing</div><div id="someotherdiv"></div></body>' ],
            [ 'Production', '<body><div id="someotherdiv"></div></body>' ],
        ];
    }

    /**
     * @test
     */
    public function showFrontendBannerWithCustomStyle()
    {
        $this->setApplicationContext('Development');

        $this->prepareDefaultSut();

        $this->sut->setConf(array_merge(
            $this->defaultConfiguration,
            array('bannerStyle' => 'custom')
        ));

        $params = array('pObj' => $GLOBALS['TSFE']);

        $this->sut->contentPostProcOutputHook($params);

        $this->assertEquals(
            '<body><div class="contextbanner" style="custom">Development</div><div id="someotherdiv"></div></body>',
            $GLOBALS['TSFE']->content
        );
    }
}
