<?php declare(strict_types=1);

namespace CarstenWindler\ContextBanner\Backend\ToolbarItems;

use CarstenWindler\ContextBanner\Main;
use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;

class ApplicationContextToolbarItem implements ToolbarItemInterface
{
    /**
     * @var Main
     */
    protected $main;

    public function __construct()
    {
        $this->main = new Main();
    }

    /**
     * Checks whether the user has access to this toolbar item.
     *
     * @return bool true if user has access, false if not
     */
    public function checkAccess()
    {
        return $this->main->isToolbarItemShown();
    }

    /**
     * Renders the toolbar icon.
     *
     * @return string HTML
     */
    public function getItem()
    {
        return $this->main->renderToolbarItem();
    }

    /**
     * Renders the drop down.
     *
     * @return string HTML
     */
    public function getDropDown()
    {
        return '';
    }

    /**
     * No additional attributes.
     *
     * @return array List item HTML attributes
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * This item has a drop down.
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return false;
    }

    /**
     * Position relative to others.
     *
     * @return int
     */
    public function getIndex()
    {
        return 0;
    }
}
