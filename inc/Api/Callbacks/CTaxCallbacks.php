<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class CTaxCallbacks extends BaseController
{
    public function ctaxSectionManager()
    {
        echo 'Manage your custom taxonomies';
    }

    public function ctaxSanitize( $input )
    {
        $output = get_option('jameedium_plugin_ctax');

        if ( isset($_POST["remove"]) )
        {
            unset($output[$_POST["remove"]]);

            return $output;
        }
        
        if ( count($output) == 0 )
        {
            $output[$input['taxonomy']] = $input;

            return $output;
        }

        foreach( $output as $key => $value )
        {
            if( $input['taxonomy'] === $key )
            {
                $output[$key] = $input;
            }
            else
            {
                $output[$input['taxonomy']] = $input;
            }
        }

        return $output;
    }

    public function textField( $args )
    {
        $name = $args['label_for'];
        $placeholder = $args['placeholder'];
        $option_name = $args['option_name'];
        $input = get_option( $option_name );
        $value = '';
        $disabled = '';

        if( isset($_POST['edit_taxonomy']) )
        {
            $value = $input[$_POST['edit_taxonomy']][$name];       
            $disabled = ($name == 'taxonomy') ? 'disabled' : '';
        }

        echo "<input type='text' class='regular-text' id='$name' name='{$option_name}[{$name}]' value='$value' placeholder='$placeholder' required $disabled>";
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

        if( isset($_POST['edit_taxonomy']) )
        {
            $checked = isset($checkbox[$_POST['edit_taxonomy']][$name]) ? 'checked' : '';
        }

        echo "<div class='$classes'><input type='checkbox' id='$name' name='{$option_name}[{$name}]' value='1' $checked><label for='$name'><div></div></label></div>";
    }

    public function checkboxPostTypeField( $args )
    {
        $name = $args['label_for'];
        $classes = $args['classes'];
        $option_name = $args['option_name'];
        $checkbox = get_option( $option_name );

        $post_types = get_post_types( array( 'show_ui' => true ) );
        
        $output .= '<details>';
        $output .= '<summary>Connect to a Post type</summary>';
        $output .= '<table class="details-table">';
        foreach ( $post_types as $post )
        {
            if( isset($_POST['edit_taxonomy']) )
            {
                $checked = isset($checkbox[$_POST['edit_taxonomy']][$name][$post]) ? 'checked' : '';
            }

            $output .= "<tr><td><label for='$post'><strong>$post</strong></label></td><td><div class='$classes'><input type='checkbox' id='$post' name='{$option_name}[{$name}][{$post}]' value='1' $checked ><label for='$post'><div></div></label></div></td></tr>";
        }
        $output .= '</table></details>';
        
        echo $output;
    }
}