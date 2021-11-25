<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use Inc\Base\BaseController;
use Inc\Api\Widgets\ShareWidget;

class ShareWidgetController extends BaseController
{
    public $subpages = array();
    public $shareWidget;

    public function register()
    {
        if ( !$this->page_activated('share_widget_manager') )
        {
            return;
        }

        /**
         *  any code must be called after this comment
         *  so it runs only if the cpt are activated
         */

        $this->shareWidget = new ShareWidget;

        add_action('init', array($this,'activate'));
    }

    public function activate()
    {
        
    }
}