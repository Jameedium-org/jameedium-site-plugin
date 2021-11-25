<?php
/**
 * @author Jameedium Development Team
 * 
 * @package jameediumtheme
 * 
 * @version 1.2.0
 */

namespace Inc\Func;

class Ajax
{
    public function register()
    {
        $option = get_option( 'jameedium_plugin' );
        $name = 'contact_form';
        $page_activated = isset($option[$name]) ? $option[$name] : false;

        if ( $page_activated ){
            add_action( 'wp_ajax_nopriv_jameedium_save_user_contact_form', array( $this, 'save_user_contact_form' ) );
            add_action( 'wp_ajax_jameedium_save_user_contact_form', array( $this, 'save_user_contact_form' ) );
        }
    }

    public function save_user_contact_form()
    {
        $title = wp_strip_all_tags($_POST['name']);
        $email = wp_strip_all_tags($_POST['email']);
        $message = wp_strip_all_tags($_POST['message']);
        $args = array(
            'post_title'    => $title,
            'post_content'  => $message,
            'post_author'   => 1,
            'post_status'   => 'publish',
            'post_type'     => 'jameedium-contact',
            'meta_input'    => array(
                '_contact_email_value_key' => $email
            )
        );
        
        $postID = wp_insert_post($args);
    
        echo $postID;
    
        die();
    }
}