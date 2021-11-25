<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\CPTCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\PluginMNGCallbacks;

class CPTController extends BaseController
{
    public $settings;
    public $cpt_callbacks;
    public $admincallbacks;
    public $subpages = array();
    public $pluginMNGCallbacks;
    public $custom_post_types = array();

    /**
     * the first function that is called by the 
     * init.php in the inc folder
     */
    public function register()
    {
        if ( !$this->page_activated('cpt_manager') )
        {
            return;
        }
        /**
         *  any code must be called after this comment
         *  so it runs only if the cpt are activated
         */

        // call the needed classes
        $this->settings = new Settings();
        $this->cpt_callbacks = new CPTCallbacks();
        $this->admincallbacks = new AdminCallbacks();
        $this->pluginMNGCallbacks = new PluginMNGCallbacks();
        
        // call the local functions needed on the registeration of the dashboard
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        
        // make the CPT Manager page
        $this->setSubPages();
        $this->settings->addSubPages( $this->subpages )->register();
        
        $this->storeCustomPostTypes();
        
        if ( !empty( $this->custom_post_types ) ){
            add_action('init', array($this,'registerCPT'));
        }
    }
    
    /**
     * declares the subpages array to be made
     */
    public function setSubPages()
    {
        $this->subpages = array(
            array(
                'parent_slug'    => 'jameedium_plugin',
                'page_title'    =>  'Custom Post Types',
                'menu_title'    =>  'CPT Manager',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_cpt',
                'callback'      =>  array( $this->admincallbacks, 'adminCPT' )
            )
        );
    }

    public function storeCustomPostTypes()
    {
        $options = get_option('jameedium_plugin_cpt') ?: array();

        foreach ( $options as $option )
        {
            $this->custom_post_types[] = array(
				'post_type'             => $option['post_type'],
				'name'                  => $option['plural_name'],
				'singular_name'         => $option['singular_name'],
				'menu_name'             => $option['plural_name'],
				'name_admin_bar'        => $option['singular_name'],
				'archives'              => $option['singular_name'] . __( ' Archives' , 'jameedium-site-plugin' ),
				'attributes'            => $option['singular_name'] . __( ' Attributes' , 'jameedium-site-plugin' ),
				'parent_item_colon'     => __( 'Parent ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'all_items'             => __( 'All ' , 'jameedium-site-plugin' ) . $option['plural_name'],
				'add_new_item'          => __( 'Add New ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'add_new'               => __( 'Add New' , 'jameedium-site-plugin' ),
				'new_item'              => __( 'New ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'edit_item'             => __( 'Edit ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'update_item'           => __( 'Update ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'view_item'             => __( 'View ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'view_items'            => __( 'View ' , 'jameedium-site-plugin' ) . $option['plural_name'],
				'search_items'          => __( 'Search ' , 'jameedium-site-plugin' ) . $option['plural_name'],
				'not_found'             => __( 'No ' , 'jameedium-site-plugin' ) . $option['singular_name'] . __( ' Found' , 'jameedium-site-plugin' ),
				'not_found_in_trash'    => __( 'No ' , 'jameedium-site-plugin' ) . $option['singular_name'] . __( ' Found in Trash' , 'jameedium-site-plugin' ),
				'featured_image'        => __( 'Featured Image' , 'jameedium-site-plugin' ),
				'set_featured_image'    => __( 'Set Featured Image' , 'jameedium-site-plugin' ),
				'remove_featured_image' => __( 'Remove Featured Image' , 'jameedium-site-plugin' ),
				'use_featured_image'    => __( 'Use Featured Image' , 'jameedium-site-plugin' ),
				'insert_into_item'      => __( 'Insert into ' , 'jameedium-site-plugin' ) . $option['singular_name'],
				'uploaded_to_this_item' => __( 'Upload to this ') . $option['singular_name'],
				'items_list'            => $option['plural_name'] . __( ' List' , 'jameedium-site-plugin' ),
				'items_list_navigation' => $option['plural_name'] . __( ' List Navigation' , 'jameedium-site-plugin' ),
				'filter_items_list'     => __( 'Filter' , 'jameedium-site-plugin' ) . $option['plural_name'] . __( ' List' , 'jameedium-site-plugin' ),
				'label'                 => $option['singular_name'],
				'description'           => $option['plural_name'] . __( 'Custom Post Type' , 'jameedium-site-plugin' ),
				'supports'              => array( 
                                                    'title',
                                                    'editor',
                                                    'thumbnail',
                                                    isset($option['comments']) ? 'comments' : ''
                                                ),
				'taxonomies'            => array(),
				'hierarchical'          => isset($option['hierarchical']) ?: false,
				'public'                => isset($option['public']) ?: false,
				'show_ui'               => isset($option['show_ui']) ?: false,
                'show_in_menu'          => isset($option['show_ui']) ?: false,
                'show_in_rest'          => isset($option['show_in_rest']) ?: false,
				'menu_position'         => ($option['menu_position'] !== null && $option['menu_position'] !== '') ? (int) $option['menu_position'] : 5,
				'show_in_admin_bar'     => isset($option['show_ui']) ?: false,
				'show_in_nav_menus'     => isset($option['public']) ?: false,
				'can_export'            => isset($option['can_export']) ?: false,
				'has_archive'           => isset($option['has_archive']) ?: false,
				'exclude_from_search'   => isset($option['exclude_from_search']) ?: false,
				'publicly_queryable'    => isset($option['publicly_queryable']) ?: false,
				'capability_type'       => ($option['capability_type'] !== null && $option['menu_position'] !== '') ? $option['menu_position'] : 'post',
                'menu_icon'             => $option['menu_icon']
			);
        }
    }

    public function registerCPT()
    {
        foreach( $this->custom_post_types as $cpt )
        {
            $labels = array(
                'name'                  => $cpt['name'],
                'singular_name'         => $cpt['singular_name'],
                'menu_name'             => $cpt['menu_name'],
                'name_admin_bar'        => $cpt['name_admin_bar'],
                'archives'              => $cpt['archives'],
                'attributes'            => $cpt['attributes'],
                'parent_item_colon'     => $cpt['parent_item_colon'],
                'all_items'             => $cpt['all_items'],
                'add_new_item'          => $cpt['add_new_item'],
                'add_new'               => $cpt['add_new'],
                'new_item'              => $cpt['new_item'],
                'edit_item'             => $cpt['edit_item'],
                'update_item'           => $cpt['update_item'],
                'view_item'             => $cpt['view_item'],
                'view_items'            => $cpt['view_items'],
                'search_items'          => $cpt['search_items'],
                'not_found'             => $cpt['not_found'],
                'not_found_in_trash'    => $cpt['not_found_in_trash'],
                'featured_image'        => $cpt['featured_image'],
                'set_featured_image'    => $cpt['set_featured_image'],
                'remove_featured_image' => $cpt['remove_featured_image'],
                'use_featured_image'    => $cpt['use_featured_image'],
                'insert_into_item'      => $cpt['insert_into_item'],
                'uploaded_to_this_item' => $cpt['uploaded_to_this_item'],
                'items_list'            => $cpt['items_list'],
                'items_list_navigation' => $cpt['items_list_navigation'],
                'filter_items_list'     => $cpt['filter_items_list']
            );
            $args = array(
                'labels'                    =>  $labels,
                'label'                     =>  $cpt['label'],
                'description'               =>  $cpt['description'],
                'supports'                  =>  $cpt['supports'],
                'taxonomies'                =>  $cpt['taxonomies'],
                'hierarchical'              =>  $cpt['hierarchical'],
                'public'                    =>  $cpt['public'],
                'show_ui'                   =>  $cpt['show_ui'],
                'show_in_menu'              =>  $cpt['show_in_menu'],
                'menu_position'             =>  $cpt['menu_position'],
                'show_in_admin_bar'         =>  $cpt['show_in_admin_bar'],
                'show_in_nav_menus'         =>  $cpt['show_in_nav_menus'],
                'show_in_rest'              =>  $cpt['show_in_rest'],
                'can_export'                =>  $cpt['can_export'],
                'has_archive'               =>  $cpt['has_archive'],
                'exclude_from_search'       =>  $cpt['exclude_from_search'],
                'publicly_queryable'        =>  $cpt['publicly_queryable'],
                'capability_type'           =>  $cpt['capability_type'],
                'menu_icon'                 =>  $cpt['menu_icon']
            );
            
            register_post_type( $cpt['post_type'], $args );
        }
    }

    public function setSettings()
    {
        $args = array(
            array(
                'option_group'  =>  'jameedium_plugin_cpt_settings',
                'option_name'   =>  'jameedium_plugin_cpt',
                'callback'      =>  array( $this->cpt_callbacks, 'cptSanitize' )
            )
        );

        $this->settings->addSettings( $args );
    }

    public function setSections()
    {
        $args = array(
            array(
                'id'        =>  'jameedium_cpt_index',
                'title'     =>  'Custom Post Type Manager',
                'callback'  =>  array( $this->cpt_callbacks, 'cptSectionManager'),
                'page'      =>  'jameedium_cpt'
            )
        );

        $this->settings->addSections( $args );
    }

    public function setFields()
    {
        $args = array(
            array(
                'id'        =>  'post_type',
                'title'     =>  __( 'Custom post type id' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'textField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'post_type',
                        'option_name'   =>  'jameedium_plugin_cpt',
                        'placeholder'   =>  'eg. product',
                        'required'      =>  true
                    )
            ),
            array(
                'id'        =>  'singular_name',
                'title'     =>  __( 'Singular name' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'textField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'singular_name',
                        'option_name'   =>  'jameedium_plugin_cpt',
                        'placeholder'   =>  'eg. product',
                        'required'      =>  true
                    )
            ),
            array(
                'id'        =>  'plural_name',
                'title'     =>  __( 'Plural name' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'textField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'plural_name',
                        'option_name'   =>  'jameedium_plugin_cpt',
                        'placeholder'   =>  'eg. products',
                        'required'      =>  true
                    )
            ),
            array(
                'id'        =>  'menu_icon',
                'title'     =>  __( 'Menu icon' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'textField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'menu_icon',
                        'option_name'   =>  'jameedium_plugin_cpt',
                        'placeholder'   =>  'eg. dashicons-admin-post',
                        'required'      =>  false
                    )
            ),
            array(
                'id'        =>  'menu_position',
                'title'     =>  __( 'Menu position' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'textField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'menu_position',
                        'option_name'   =>  'jameedium_plugin_cpt',
                        'placeholder'   =>  '5',
                        'type'          =>  'number',
                        'required'      =>  false
                    )
            ),
            array(
                'id'        =>  'public',
                'title'     =>  __( 'Public' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'public',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'has_archive',
                'title'     =>  __( 'Has archive' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'has_archive',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'comments',
                'title'     =>  __( 'Comments' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'comments',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'show_in_rest',
                'title'     =>  __( 'Block Editor' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'show_in_rest',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'show_ui',
                'title'     =>  __( 'Show UI' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'show_ui',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'hierarchical',
                'title'     =>  __( 'Hierarchical' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'hierarchical',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'exclude_from_search',
                'title'     =>  __( 'Exclude from search' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'exclude_from_search',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'can_export',
                'title'     =>  __( 'Exportable' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'can_export',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            ),
            array(
                'id'        =>  'publicly_queryable',
                'title'     =>  __( 'Publicly Queryable' , 'jameedium-site-plugin' ),
                'callback'  =>  array( $this->cpt_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_cpt',
                'section'   =>  'jameedium_cpt_index',
                'args'      =>  array(
                        'label_for'     =>  'publicly_queryable',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_cpt'
                    )
            )
        );

        $this->settings->addFields( $args );
    }
}