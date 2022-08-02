<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

/*
Plugin Name: Jameedium Site Plugin
Plugin URI: https://dev.jameedium.org/jameedium-site-plugin
Description: A plugin that makes Jameedium site possible.
Version: 1.2.0
Author: Jameedium Dev Team
Author URI: https://dev.jameedium.org/
License: GPLv2 or later
Text Domain: jameedium-site-plugin
*/

/*      This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// a security check if the wordpress started properly
defined('ABSPATH') or die('حياك الله... روح اشرب شاي بنعنع.. انداري شو جايبك هون!!');

//requires once the composer autoload functionality
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php'))
{
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * runs at the plugin activation
 */
function activate_jameedium_plugin()
{
    Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__ , 'activate_jameedium_plugin' );

/**
 * runs at the plugin deactivation
 */
function deactivate_jameedium_plugin()
{
    Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__ , 'deactivate_jameedium_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists('Inc\\Init') )
{
    Inc\Init::register_services();
}