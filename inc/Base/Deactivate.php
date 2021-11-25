<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base;

class Deactivate
{
    public static function deactivate()
    {
        //runs on plugin deactivation
        flush_rewrite_rules();
    }
}