<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base;

class Activate
{
    public static function activate()
    {
        //runs on plugin activation
        flush_rewrite_rules();

        $default = array();

        if ( ! get_option( 'jameedium_plugin' ) )
        {
            update_option( 'jameedium_plugin' , $default );
        }

        if ( ! get_option( 'jameedium_plugin_cpt' ) )
        {
            update_option( 'jameedium_plugin_cpt' , $default );
        }

        if ( ! get_option( 'jameedium_plugin_ctax' ) )
        {
            update_option( 'jameedium_plugin_ctax' , $default );
        }
    }
}