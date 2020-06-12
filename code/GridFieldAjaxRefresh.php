<?php

/**
 * This component provides a automatic refreshing of a gridfield at a particular interval, or manual refreshing with
 * a "refresh" button {@link GridField}
 *
 * @package GridFieldAjaxRefresh
 */
namespace GridFieldAjaxRefresh;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_HTMLProvider;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class GridFieldAjaxRefresh implements GridField_HTMLProvider
{

    private static $auto_refresh_enabled = false;
    private static $auto_refresh_interval = 180000; // 180000 = 3 min

    protected $refreshDelay;    //in milliseconds: 1000 ms = 1 second
    protected $autoRefresh;

    /**
     * The HTML fragment to write this component into
     */
    protected $targetFragment;

    /**
     * @param int $refreshDelay The Delay in milliseconds between refreshes
     * @param bool $autoRefresh True to enable automatic refresh, False to use manual refresh with a button
     */
    public function __construct($refreshDelay = 1000, $autoRefresh = true, $targetFragment = 'before')
    {
        $this->refreshDelay = $refreshDelay;
        $this->autoRefresh = $autoRefresh;
        $this->targetFragment = $targetFragment;
    }

    /**
     * Returns a map where the keys are fragment names and the values are pieces of HTML to add to these fragments.
     * @param GridField $gridField Grid Field Reference
     * @return Array Map where the keys are fragment names and the values are pieces of HTML to add to these fragments.
     */
    public function getHTMLFragments($gridField)
    {
        Requirements::css('gridfieldajaxrefresh/css/GridFieldAjaxRefresh.css');
        Requirements::javascript('gridfieldajaxrefresh/javascript/GridFieldAjaxRefresh.js');

        $data = array('RefreshDelay' => $this->refreshDelay,
                    'AutoRefresh' => $this->autoRefresh,
                    'GridFieldID' => $gridField->ID());

        $forTemplate = new ArrayData($data);
        $args = array(
            'ID' => $gridField->ID(),
        );

        return array(
            $this->targetFragment => $forTemplate->renderWith('GridFieldAjaxRefresh_Header', $args)
        );
    }
}
