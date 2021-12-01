<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{
    public function register()
    {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
    }

    public function enqueueAdminScripts()
    {
        // enqueue all our scripts
        wp_enqueue_style( 'mypluginstyle', $this->plugin_url . 'dist/main.css' );
        wp_enqueue_script( 'mypluginscript', $this->plugin_url . 'dist/main.js' );
    }
}