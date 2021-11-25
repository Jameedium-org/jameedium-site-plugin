<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Func;

class ShortCodes
{
    public function register()
    {
        $option = get_option( 'jameedium_plugin' );
        $name = 'contact_form';
        $page_activated = isset($option[$name]) ? $option[$name] : false;

        if ( $page_activated ){
            add_shortcode( 'jameedium_contact_form', array( $this, 'contact_form' ) );
        }
    }
    
    public function contact_form($atts , $content = null ){
        //[jameedium_contact_form]
        $atts = shortcode_atts(
                array(),
                $atts,
                'jameeedium_contact_form'
         );

        $ajax_url = admin_url('admin-ajax.php');
    
        //return html
        ob_start();
        echo get_option( 'jameedium_plugin_contact' );
        $out = "<script>
        jQuery(document).ready(function($){

            $('#jameediumcontactform #name').on('change', function() {
            // do something
            $('#name').removeClass('bg-danger');
            });

            $('#jameediumcontactform #email').on('change', function() {
            // do something
            $('#email').removeClass('bg-danger');
            });

            $('#jameediumcontactform #message').on('change', function() {
            // do something
            $('#message').removeClass('bg-danger');
            });

            //contact form submission
            $('#jameediumcontactform').on('submit',function(e){

            e.preventDefault();
            let form = $(this);
            let name = form.find('#name').val();
            let email = form.find('#email').val();
            let message = form.find('#message').val();
            let ajaxurl = '$ajax_url';

            if( name === ''){
                $('#name').addClass('bg-danger');
                return;
            }
            else if( email === ''){
                $('#email').addClass('bg-danger');
                return;
            }
            else if( message === ''){
                $('#message').addClass('bg-danger');
                return;
            }

            $.ajax({

                url : ajaxurl,
                type : 'post',
                data : {
                    name : name,
                    email : email,
                    message : message,
                    action : 'jameedium_save_user_contact_form'
                },
                error : function( response ){
                    alert('حدث خطأ' + response + '... نرجو منك اعادة المحاولة');
                },
                success : function(response){
                    if (response == 0){
                    alert('حدث خطأ... نرجو منك اعادة المحاولة');
                    }
                    else{
                        $('#name').addClass('bg-success');
                        $('#email').addClass('bg-success');
                        $('#message').addClass('bg-success');
                        alert('شكرًا جزيلا على تواصلك معنا');
                    }
                }
            });

            });

        });
        </script>";

        echo $out;
        return ob_get_clean();
    }
}
