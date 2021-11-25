<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base;

use \Inc\Base\BaseController;

final class SettingsLinks extends BaseController
{
    public function register()
    {
        add_action( "plugin_action_links_" . $this->plugin , array( $this , 'settings_link') );
    }

    function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=jameedium_plugin">Settings</a>';
        array_push($links,$settings_link);
        return $links;
    }
}