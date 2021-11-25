<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ContactCallbacks;

class ContactFormController extends BaseController
{
    public $subpages = array();
    public $settings;
    public $adminCallbacks;
    public $contactCallbacks;

    public function register()
    {
        if ( !$this->page_activated('contact_form') )
        {
            return;
        }
        
        /**
         *  any code must be called after this comment
         *  so it runs only if the cpt are activated
         */

        // call the needed classes
        $this->settings = new Settings();
        $this->adminCallbacks = new AdminCallbacks();
        $this->contactCallbacks = new ContactCallbacks();
        
        // call the local functions needed on the registeration of the dashboard
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        
        // make the Contact Form page
        $this->setSubPages();
        $this->settings->addSubPages( $this->subpages )->register();
    }

    /**
     * declares the subpages array to be made
     */
    public function setSubPages()
    {
        $this->subpages = array(
            array(
                'parent_slug'    => 'jameedium_plugin',
                'page_title'    =>  'Contact Form',
                'menu_title'    =>  'Contact Form',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_contact_form',
                'callback'      =>  array( $this->adminCallbacks, 'adminContact' )
            )
        );
    }

   
    public function setSettings()
    {
        $args = array(
            array(
                'option_group'  =>  'jameedium_plugin_contact_settings',
                'option_name'   =>  'jameedium_plugin_contact',
                'callback'      =>  array( $this->contactCallbacks, 'textAreaSanitize' )
            )
        );

        $this->settings->addSettings( $args );
    }

    public function setSections()
    {
        $args = array(
            array(
                'id'        =>  'jameedium_contact_index',
                'title'     =>  'Contact Form Manager',
                'callback'  =>  array( $this->contactCallbacks, 'sectionManager'),
                'page'      =>  'jameedium_contact_form'
            )
        );

        $this->settings->addSections( $args );
    }

    public function setFields()
    {
        $args = array(
            array(
                'id'        =>  'jameedium_plugin_contact',
                'title'     =>  'Contact Form',
                'callback'  =>  array( $this->contactCallbacks, 'textArea' ),
                'page'      =>  'jameedium_contact_form',
                'section'   =>  'jameedium_contact_index',
                'args'      =>  array(
                        'label_for'     =>  'jameedium_plugin_contact',
                        'option_name'   =>  'jameedium_plugin_contact',
                        'rows'          =>  20,
                        'cols'          =>  100,
                        'style'         =>  '',
                        'placeholder'   =>  ''
                    )
            )
        ); 

        $this->settings->addFields( $args );
    }
}