<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;

class MembershipController extends BaseController
{
    public $subpages = array();
    public $settings;
    public $admincallbacks;

    public function register()
    {
        if ( !$this->page_activated('membership_manager') )
        {
            return;
        }
        
        /**
         *  any code must be called after this comment
         *  so it runs only if the cpt are activated
         */
        $this->settings = new Settings();
        $this->admincallbacks = new AdminCallbacks();

        $this->setSubPages();
        $this->settings->addSubPages( $this->subpages )->register();

        add_action('init', array($this,'activate'));
    }

    public function activate()
    {
        
    }

    /**
     * declares the subpages array to be made
     */
    public function setSubPages()
    {
        $this->subpages = array(
            array(
                'parent_slug'    => 'jameedium_plugin',
                'page_title'    =>  'Membership Manager',
                'menu_title'    =>  'Membership',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_membership',
                'callback'      =>  array( $this->admincallbacks, 'adminMembership' )
            )
        );
    }
}