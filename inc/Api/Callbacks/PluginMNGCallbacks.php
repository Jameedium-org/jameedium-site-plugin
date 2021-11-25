<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class PluginMNGCallbacks extends BaseController
{
    public function checkboxSanitize($input)
    {
        $output = array();

        foreach ( $this->managers as $manager => $title)
        {
            $output[$manager] = isset($input[$manager]) ? true : false;
        }

        return $output;
    }

    public function adminSectionManager()
    {
        echo 'Manage the features by activating the things you need';
    }

    public function checkboxField( $args )
    {
        $name = $args['label_for'];
        $classes = $args['classes'];
        $option_name = $args['option_name'];
        $checkbox = get_option( $option_name );

        $checkstate = isset($checkbox[$name]) ? ($checkbox[$name] ? 'checked' : '') : '';

        echo "<div class='$classes'><input type='checkbox' id='$name' name='{$option_name}[{$name}]' value='1' class='$checkstate' $checkstate><label for='$name'><div></div></label></div>";
    }
}