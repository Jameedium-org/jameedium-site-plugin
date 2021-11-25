<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once("$this->plugin_path/templates/AdminDashboard.php");
    }

    public function adminContact()
    {
        return require_once("$this->plugin_path/templates/AdminContactForm.php");
    }

    public function adminCPT()
    {
        return require_once("$this->plugin_path/templates/AdminCPT.php");
    }

    public function adminCTax()
    {
        return require_once("$this->plugin_path/templates/AdminCTax.php");
    }

    public function adminShareWidget()
    {
        return require_once("$this->plugin_path/templates/AdminShareWidget.php");
    }

    public function adminGallery()
    {
        return require_once("$this->plugin_path/templates/AdminGallery.php");
    }

    public function adminTestimonial()
    {
        return require_once("$this->plugin_path/templates/AdminTestimonial.php");
    }

    public function adminTemplates()
    {
        return require_once("$this->plugin_path/templates/AdminTemplates.php");
    }

    public function adminLogin()
    {
        return require_once("$this->plugin_path/templates/AdminLogin.php");
    }

    public function adminMembership()
    {
        return require_once("$this->plugin_path/templates/AdminMembership.php");
    }
}