<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array full list of classes
     */
    public static function get_services()
    {
        return[
            Pages\Dashboard::class,
            Func\Ajax::class,
            Func\ShortCodes::class,
            Func\CustomPostTypes::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\Controllers\ContactFormController::class,
            Base\Controllers\CPTController::class,
            Base\Controllers\CTaxController::class,
            Base\Controllers\ShareWidgetController::class,
            Base\Controllers\GalleryController::class,
            Base\Controllers\TestimonialController::class,
            Base\Controllers\TemplatesController::class,
            Base\Controllers\LoginController::class,
            Base\Controllers\MembershipController::class
        ];
    }
    /**
     * Loops through the classes, initializes them,
     * and calls the register() method if it exists
     * @return
     */
    public static function register_services()
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