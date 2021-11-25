<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base;

class BaseController
{
    public $plugin;

    public $plugin_url;
    
    public $plugin_path;

    public $managers = array();

    public function __construct()
    {
        //the publicly used variables
        $this->plugin_url = plugin_dir_url( dirname( __FILE__ , 2 ) );
        $this->plugin_path = plugin_dir_path( dirname( __FILE__ , 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__ , 3 ) ) . '/jameedium-site-plugin.php';

        //all the managers that are in the plugin
        $this->managers = array(
            'contact_form'          =>  'Activate Contact Form',
            'cpt_manager'           =>  'Activate CPT Manager',
            'taxonomy_manager'      =>  'Activate Taxonomy Manager',
            'share_widget_manager'  =>  'Activate The Share Widget',
            'gallery_manager'       =>  'Activate Gallery Manager',
            'testimonial_manager'   =>  'Activate Testimonial Manager',
            'templates_manager'     =>  'Activate Templates Manager',
            'login_manager'         =>  'Activate Ajax Login Manager',
            'membership_manager'    =>  'Activate Membership Manager',
        );

        //all the options that need to refresh_rewrite_rules after updating
    }

    /**
     * @param   name of the option that activates the page
     * @return  false if the name is empty or if the page is deactivated in the dashboard settings
     * @return  true if the page is activated in the dashboard settings
     */
    public function page_activated( string $name )
    {
        if ( empty($name) ){
            return false;
        }

        $option = get_option( 'jameedium_plugin' );
        $page_activated = isset($option[$name]) ? $option[$name] : false;

        return $page_activated;
    }
}