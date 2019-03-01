[![Latest Stable Version](https://poser.pugx.org/carstenwindler/context_banner/version)](https://packagist.org/packages/carstenwindler/context_banner)
[![Build Status](https://api.travis-ci.org/carstenwindler/context_banner.svg?branch=master)](https://travis-ci.org/carstenwindler/cwenvbanner)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/carstenwindler/context_banner/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/carstenwindler/context_banner/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/carstenwindler/context_banner/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/carstenwindler/context_banner/?branch=master)

# context_banner for Typo3

Ever been in the wrong browser tab and accidentally made changes on Production, and not your local dev? Then this extension could be useful for you.

It displays a small banner in Frontend and Backend to better show the current application context (Development - Green, Testing - Yellow, Production - Read), so you can easily see if you are in the right Typo3 backend.

## Installation

Just activate the extension in the Extension Manager. 

## Options

The following options can be configured:

* pageTitleTemplate - Simple templating for the page title
* bannerTemplate - Simple templating for the banner text 
* bannerStyle - Whether to use automatically styling by Application context or custom
* bannerCssCustom - CSS to be used for custom banner style
* bannerCssDevelopment - CSS to be used for Development banner style
* bannerCssTesting - CSS to be used for Testing banner style
* bannerCssProduction - CSS to be used for Production banner style
* showBannerOnProduction - whether the banner should be shown in the frontend for 

## TODO

* Use middleware for Typo3 v9

## Testing

To run the test:

```.Build/vendor/bin/phpunit -c Tests/Build/UnitTests.xml```

Code Sniffer

```.Build/vendor/bin/phpcs --standard=PSR2 Classes```