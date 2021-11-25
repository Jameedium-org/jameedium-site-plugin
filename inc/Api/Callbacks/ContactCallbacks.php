<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class ContactCallbacks extends BaseController
{
    public function sectionManager()
    {
        echo 'Manage your contact form <br/><br/>';
        echo '<code>[jameedium_contact_form]</code>';
    }

    public function textAreaSanitize( $input )
    {
        $output = ( $input );
        return $output;
    }

    public function textArea( $args )
    {
        $rows = isset( $args['rows'] ) ? $args['rows'] : 4 ;
        $cols = isset( $args['cols'] ) ? $args['cols'] : 55 ;
        $style = isset( $args['style'] ) ? $args['style'] : '' ;
        $name = $args['label_for'];
        $classes = isset( $args['classes'] ) ? $args['classes'] : '' ;
        $option_name = $args['option_name'];
        $placeholder = $args['placeholder'];

        $content = esc_html( get_option( $option_name ) );
        
        echo "<textarea style='{$style}' class='{$classes}' name='{$option_name}' rows='{$rows}' cols='{$cols}' placeholder='{$placeholder}'>{$content}</textarea>";
    }
}