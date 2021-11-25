<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Pages;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\PluginMNGCallbacks;

class Dashboard extends BaseController
{
    public $settings;
    public $pages = array();
    public $admincallbacks;
    public $pluginMNGCallbacks;

    /**
     * the first function that is called by the 
     * init.php in the inc folder
     */
    public function register()
    {
        // call the needed classes
        $this->settings = new Settings();
        $this->admincallbacks = new AdminCallbacks();
        $this->pluginMNGCallbacks = new PluginMNGCallbacks();

        // call the pages and subpages declaration functions
        $this->setPages();

        // call the local functions needed on the registeration of the dashboard
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        // call the custom settings api page builder that uses the built-in add_menu_page() wordpress function
        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
    }

    /**
     * declares the pages array to be made
     */
    public function setPages()
    {
        $this->pages = array(
            array(
                'page_title'    =>  'Jameedium Plugin',
                'menu_title'    =>  'Plugin',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_plugin',
                'callback'      =>  array( $this->admincallbacks, 'adminDashboard' ),
                'icon_url'      =>  get_template_directory_uri() . '/img/logo-20px.png',
                'position'      =>  110
            )
        );
    }

    public function setSettings()
    {
        $args[] = array(
            'option_group'  =>  'jameedium_plugin_settings',
            'option_name'   =>  'jameedium_plugin',
            'callback'      =>  array( $this->pluginMNGCallbacks, 'checkboxSanitize' )
        );

        $this->settings->addSettings( $args );
    }

    public function setSections()
    {
        $args = array(
            array(
                'id'        =>  'jameedium_admin_index',
                'title'     =>  'Settings Manager',
                'callback'  =>  array( $this->pluginMNGCallbacks, 'adminSectionManager'),
                'page'      =>  'jameedium_plugin'
            )
        );

        $this->settings->addSections( $args );
    }

    public function setFields()
    {
        $args = array();

        foreach( $this->managers as $manager => $title )
        {
            $args[] = array(
                'id'        =>  $manager,
                'title'     =>  $title,
                'callback'  =>  array( $this->pluginMNGCallbacks, 'checkboxField' ),
                'page'      =>  'jameedium_plugin',
                'section'   =>  'jameedium_admin_index',
                'args'      =>  array(
                    'label_for'     =>  $manager,
                    'classes'       =>  'ui-toggle',
                    'option_name'   =>  'jameedium_plugin'
                )
            );
        }

        $this->settings->addFields( $args );
    }
}