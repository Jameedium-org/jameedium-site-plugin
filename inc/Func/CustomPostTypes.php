<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Func;

class CustomPostTypes
{
    /**
     * returns all the post types classes that need to be activated
     * @return array full list of all the post types classes that need to be activated
     */
    public static function get_services()
    {
        $out = [];

        $option = get_option( 'jameedium_plugin' );
        $name = 'contact_form';
        $page_activated = isset($option[$name]) ? $option[$name] : false;

        if ( $page_activated ){
            array_push($out,CPT\ContactCPT::class);
        }

        //return all the post types classes that need to activated
        return $out;
    }
    
    /**
     * Loops through the classes, initializes them,
     * and calls the register() method if it exists
     */
    public static function register()
    {
        foreach( self::get_services() as $class )
        {
            $service = self::instantiate($class);
            if(method_exists( $service , 'register' ))
            {
                $service->register();
            }
        }
    }

    /**
     * Initializes the class
     * @param class $class      class from the services array
     * @return class instance   new instance of the class
     */
    private static function instantiate($class)
    {
        return new $class();
    }
}