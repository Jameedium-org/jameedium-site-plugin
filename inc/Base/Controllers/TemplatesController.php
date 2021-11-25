<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;

class TemplatesController extends BaseController
{
    public $subpages = array();
    public $settings;
    public $admincallbacks;

    public function register()
    {
        if ( !$this->page_activated('templates_manager') )
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
                'page_title'    =>  'Templates Manager',
                'menu_title'    =>  'Templates',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_templates',
                'callback'      =>  array( $this->admincallbacks, 'adminTemplates' )
            )
        );
    }
}