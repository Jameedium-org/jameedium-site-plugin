<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Func\CPT;

class ContactCPT
{
    public function register()
    {
        add_action( 'init', array( $this, 'initialize' ) );

        add_filter( 'manage_jameedium-contact_posts_columns', array( $this, 'columns' ) );
        add_action( 'manage_jameedium-contact_posts_custom_column', array( $this, 'columns_values' ), 10, 2);

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_contact_email_data' ) );
    }

    /* Contact CPT */

    public function initialize()
    {
        $labels = array(
            'name'              =>  'Messages',
            'singular_name'     =>  'Message',
            'menu_name'         =>  'Messages',
            'name_admin_bar'    =>  'Message'
        );
        $args = array(
            'labels'            =>  $labels,
            'show_ui'           =>  true,
            'show_in_menu'      =>  true,
            'capability_type'   =>  'post',
            'hierarchical'      =>  false,
            'menu_icon'         =>  'dashicons-email-alt',
            'supports'          =>  array('title','editor','author')
        );
        register_post_type( 'jameedium-contact' , $args );
    }
        
    public function columns( $columns )
    {
        $newColumns = array();

        $newColumns['title'] = 'Full Name';
        $newColumns['message'] = 'Message';
        $newColumns['email'] = 'Email';
        $newColumns['date'] = 'Date';

        return $newColumns;
    }

    public function columns_values( $column , $post_id)
    {
        switch($column){
            case 'message' :
                echo get_the_excerpt();
                break;
            case 'email' :
                $email = get_post_meta( $post_id, '_contact_email_value_key', true);
                echo "<a href='mailto:{$email}'>{$email}</a>";
                break;
        }
    }

    /* Contact meta boxes */

    public function add_meta_box()
    {
        add_meta_box( 'contact_email', 'Email Address', array( $this, 'contact_email_callback' ), 'jameedium-contact' ,'side');
    }

    /* metaboxes callbacks */
    public function contact_email_callback( $post )
    {

        wp_nonce_field( 'jameedium_save_contact_email_data', 'jameedium_contact_email_meta_box_nonce' );

        $value = get_post_meta( $post->ID, '_contact_email_value_key', true);

        if ( gettype($value) == 'string' ){
            $value = esc_attr( $value );
        }
        else {
            $value = '';
        }

        echo "<label for='jameedium_contact_email_field'>Email Address: </label>";
        echo "<input type='email' id='jameedium_contact_email_field' name='jameedium_contact_email_field' value='{$value}' size='25' />";
    }

    public function save_contact_email_data( $post_id )
    {

        if( ! isset( $_POST['jameedium_contact_email_meta_box_nonce'] )){
            return;
        }
        if( ! wp_verify_nonce( $_POST['jameedium_contact_email_meta_box_nonce'], 'jameedium_save_contact_email_data')){
            return;
        }
        if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return;
        }
        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }
        if( ! isset( $_POST['jameedium_contact_email_field'] ) ){
            return;
        }

        $email_val = sanitize_email( $_POST['jameedium_contact_email_field'] );

        update_post_meta( $post_id, '_contact_email_value_key', $email_val );
    }
}