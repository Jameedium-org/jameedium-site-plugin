<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class CPTCallbacks extends BaseController
{
    public function cptSectionManager()
    {
        echo 'Manage your custom post types';
    }

    public function cptSanitize( $input )
    {
        $output = get_option('jameedium_plugin_cpt');

        if ( isset($_POST["remove"]) )
        {
            unset($output[$_POST["remove"]]);

            return $output;
        }
        
        if ( count($output) == 0 )
        {
            $output[$input['post_type']] = $input;

            return $output;
        }

        foreach( $output as $key => $value )
        {
            if( $input['post_type'] === $key )
            {
                $output[$key] = $input;
            }
            else
            {
                $output[$input['post_type']] = $input;
            }
        }

        return $output;
    }

    public function textField( $args )
    {
        $name = $args['label_for'];
        $placeholder = $args['placeholder'];
        $option_name = $args['option_name'];
        $required = isset($args['required']) ? $args['required'] : false;
        $type = isset($args['type']) ? $args['type'] : 'text';
        $input = get_option( $option_name );
        $value = '';
        $disabled = '';

        if( isset($_POST['edit_post']) )
        {
            $value = $input[$_POST['edit_post']][$name];       
            $disabled = ($name == 'post_type') ? 'disabled' : '';
        }

        echo "<input type='{$type}' class='regular-text' id='$name' name='{$option_name}[{$name}]' value='$value' placeholder='$placeholder' $required $disabled>";

        // submits the stored post ID value
        // it is hidden to make it uneditable
        if ( !empty($disabled) )
        {
            echo "<input type='hidden' name='{$option_name}[{$name}]' value='$value'>";
        }
    }

    public function checkboxField( $args )
    {
        $name = $args['label_for'];
        $classes = $args['classes'];
        $option_name = $args['option_name'];
        $checkbox = get_option( $option_name );

        if( isset($_POST['edit_post']) )
        {
            $checked = isset($checkbox[$_POST['edit_post']][$name]) ? 'checked' : '';
        }

        echo "<div class='$classes'><input type='checkbox' id='$name' name='{$option_name}[{$name}]' value='1' $checked><label for='$name'><div></div></label></div>";
    }
}