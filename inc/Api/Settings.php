<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Api;

final class Settings
{
    public $admin_pages = array();
    public $admin_subpages = array();
    public $settings = array();
    public $sections = array();
    public $fields = array();
    
    /**
     * the first function that is called by the 
     * init.php in the inc folder
     */
    public function register()
    {
        if ( !empty( $this->admin_pages ) || !empty($this->admin_subpages) ){
            add_action( 'admin_menu', array( $this , 'addAdminMenu' ) );
        }

        if ( !empty( $this->settings ) ){
            add_action( 'admin_init', array( $this , 'registerCustomFields' ) );
        }
    }

    /**
     * @param array with the pages and there attributes to be created
     * @return Settings Class for method chaining
     */
    public function addPages( array $pages )
    {
        $this->admin_pages = $pages;

        return $this;
    }

    /**
     * It gives the main page a different subpage name when there is subpages
     * @param title the desired subpage name for the main page
     * @return Settings Class for method chaining
     */
    public function withSubPage( string $title = null )
    {
        if ( empty($this->admin_pages) ){
            return $this;
        }
        
        $admin_page = $this->admin_pages[0];
        $sub_page = array(
            array(
                'parent_slug'    =>  $admin_page['menu_slug'],
                'page_title'    =>  $admin_page['page_title'],
                'menu_title'    =>  ($title) ? $title : $admin_page['menu_title'],
                'capability'    =>  $admin_page['capability'],
                'menu_slug'     =>  $admin_page['menu_slug'],
                'callback'      =>  $admin_page['callback']
            )
        );

        $this->admin_subpages = $sub_page;

        return $this;
    }

    /**
     * @param array with the subpages and there attributes to be created
     * @return Settings Class for method chaining
     */
    public function addSubPages( array $pages )
    {
        $this->admin_subpages = array_merge( $this->admin_subpages, $pages );
        return $this;
    }

    /**
     * adds an the admin menu passed in the 
     * function addPages()
     */
    function addAdminMenu()
    {
        foreach ( $this->admin_pages as $page )
        {
            add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
        }
        foreach ( $this->admin_subpages as $page )
        {
            add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
        }
    }

    /**
     * @param array with the settings and there attributes to
     * be created
     * @return Settings Class for method chaining
     */
    public function addSettings( array $settings )
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param array with the sections and there attributes to
     * be created
     * @return Settings Class for method chaining
     */
    public function addSections( array $sections )
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * @param array with the fields and there attributes to
     * be created
     * @return Settings Class for method chaining
     */
    public function addFields( array $fields )
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     *  registers the custom fields that was passed in the 
     *  functions addFields(), addSections()
     *  and addSettings()
     */
    public function registerCustomFields()
    {
        // register setting
        foreach( $this->settings as $setting )
        {
            if (! ( isset( $setting['callback'] ) ) ){ $setting['callback'] = ''; }
            register_setting( $setting['option_group'], $setting['option_name'], $setting['callback'] );
        }
        // add settings section
        foreach( $this->sections as $section )
        {
            if (! ( isset( $section['callback'] ) ) ){ $section['callback'] = ''; }
            add_settings_section( $section['id'], $section['title'], $section['callback'], $section['page'] );
        }

        // add settings field
        foreach( $this->fields as $field )
        {
            if (! ( isset( $field['callback'] ) ) ){ $field['callback'] = ''; }
            if (! ( isset( $field['args'] ) ) ){ $field['args'] = ''; }
            add_settings_field( $field['id'], $field['title'], $field['callback'], $field['page'], $field['section'], $field['args'] );
        }
    }
}