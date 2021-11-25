<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Widgets;

use WP_Widget;

class ShareWidget extends WP_Widget
{
    public $widget_ID;
    public $widget_name;
    public $widget_options = array();
    public $control_options = array();

    public function __construct()
    {
        $this->widget_ID = 'jameedium_share_widget';
        $this->widget_name = 'Jameedium Share Widget';
        $this->widget_options = array(
            'Classname'                     =>  $this->widget_ID,
            'description'                   =>  $this->widget_name,
            'customize_selective_refresh'   =>  true
        );

        $this->control_options = array(
            'width'     =>  400,
            'height'    =>  350
        );
    }

    public function register()
    {
        parent::__construct( $this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options );

        add_action( 'widget_init', array( $this, 'widgetInit' ) );
    }

    public function widgetInit()
    {
        register_widget( $this );
    }

    // widget()     ====> front-end

    // form()       ====> admin-area

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Custom Text' , 'jameedium_plugin');
        $title_id = esc_attr($this->get_field_id('title'));
        $title_name = esc_attr($this->get_field_name('title'));

        ?>

        <p>
            <label for="<?php echo $title_id; ?>">Title</label>
            <input type="text" class="widefat" id="<?php echo $title_id; ?>" name="<?php echo $title_name; ?>" value="<?php esc_attr( $title ); ?>" >
        </p>

        <?php
    }

    // update()     ====> update the info
}