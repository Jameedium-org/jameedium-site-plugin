<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

namespace Inc\Base\Controllers;

use \Inc\Api\Settings;
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\CTaxCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\PluginMNGCallbacks;

class CTaxController extends BaseController
{
    public $settings;
    public $ctax_callbacks;
    public $admincallbacks;
    public $subpages = array();
    public $pluginMNGCallbacks;
    public $taxonomies = array();

    public function register()
    {

        if ( !$this->page_activated('taxonomy_manager') ){return;}

        /**
         *  any code must be called after this comment
         *  so it runs only if the ctax are activated
         */

        $this->settings = new Settings();
        $this->ctax_callbacks = new CTaxCallbacks();
        $this->admincallbacks = new AdminCallbacks();
        $this->pluginMNGCallbacks = new PluginMNGCallbacks();

        // call the local functions needed on the registeration of the dashboard
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        // make the CPT Manager page
        $this->setSubPages();
        $this->settings->addSubPages( $this->subpages )->register();

        $this->storeCustomTaxonomies();

        if ( !empty( $this->taxonomies ) )
        {
            add_action('init', array($this,'registerCTax'));
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
                'page_title'    =>  'Custom Tax Types',
                'menu_title'    =>  'CTax Manager',
                'capability'    =>  'manage_options',
                'menu_slug'     =>  'jameedium_ctax',
                'callback'      =>  array( $this->admincallbacks, 'adminCTax' )
            )
        );
    }

    public function storeCustomTaxonomies()
    {
        $options = get_option('jameedium_plugin_ctax') ?: array();

        foreach ( $options as $option )
        {
            $labels = array(
				'name'              => $option['taxonomy'],
				'singular_name'     => $option['singular_name'],
				'search_items'      => 'Search ' . $option['singular_name'],
				'all_items'         => 'All ' . $option['singular_name'],
				'parent_item'       => 'Parent ' . $option['singular_name'],
				'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
				'edit_item'         => 'Edit ' . $option['singular_name'],
				'update_item'       => 'Update ' . $option['singular_name'],
				'add_new_item'      => 'Add New ' . $option['singular_name'],
				'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
				'menu_name'         => $option['singular_name'],
			);

			$this->taxonomies[] = array(
				'hierarchical'          => isset($option['hierarchical']) ? true : false,
				'labels'                => $labels,
                'show_ui'               => isset($option['show_ui']) ? true : false,
                'show_in_rest'          => isset($option['show_in_rest']) ? true : false,
				'show_admin_column'     => isset($option['show_admin_column']) ? true : false,
				'query_var'             => true,
                'rewrite'               => array( 'slug' => $option['taxonomy'] ),
                'public'                => isset($option['public']) ? true : false,
                'publicly_queryable'    => isset($option['publicly_queryable']) ? true : false,
                'objects'               => isset($option['objects']) ? $option['objects'] : null
			);
        }
    }

    public function registerCTax()
    {
        foreach( $this->taxonomies as $ctax )
        {
            $post_types = isset($ctax['objects']) ? array_keys( $ctax['objects'] ) : null;
            register_taxonomy( $ctax['labels']['name'], $post_types , $ctax );
        }
    }

    public function setSettings()
    {
        $args = array(
            array(
                'option_group'  =>  'jameedium_plugin_ctax_settings',
                'option_name'   =>  'jameedium_plugin_ctax',
                'callback'      =>  array( $this->ctax_callbacks, 'ctaxSanitize' )
            )
        );

        $this->settings->addSettings( $args );
    }

    public function setSections()
    {
        $args = array(
            array(
                'id'        =>  'jameedium_ctax_index',
                'title'     =>  'Custom Taxonomy Manager',
                'callback'  =>  array( $this->ctax_callbacks, 'ctaxSectionManager'),
                'page'      =>  'jameedium_ctax'
            )
        );

        $this->settings->addSections( $args );
    }

    public function setFields()
    {
        $args = array(
            array(
                'id'        =>  'taxonomy',
                'title'     =>  'Custom Taxonomy id',
                'callback'  =>  array( $this->ctax_callbacks, 'textField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'taxonomy',
                        'option_name'   =>  'jameedium_plugin_ctax',
                        'placeholder'   =>  'eg. genre'
                    )
            ),
            array(
                'id'        =>  'singular_name',
                'title'     =>  'Singular name',
                'callback'  =>  array( $this->ctax_callbacks, 'textField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'singular_name',
                        'option_name'   =>  'jameedium_plugin_ctax',
                        'placeholder'   =>  'eg. genre'
                    )
            ),
            array(
                'id'        =>  'plural_name',
                'title'     =>  'Plural name',
                'callback'  =>  array( $this->ctax_callbacks, 'textField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'plural_name',
                        'option_name'   =>  'jameedium_plugin_ctax',
                        'placeholder'   =>  'eg. genres'
                    )
            ),
            array(
                'id'        =>  'hierarchical',
                'title'     =>  'Hierarchical',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'hierarchical',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'show_in_rest',
                'title'     =>  'Block Editor',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'show_in_rest',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'public',
                'title'     =>  'Public',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'public',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'publicly_queryable',
                'title'     =>  'Publicly Queryable',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'publicly_queryable',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'show_ui',
                'title'     =>  'Show UI',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'show_ui',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'show_admin_column',
                'title'     =>  'Show admin column',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'show_admin_column',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            ),
            array(
                'id'        =>  'objects',
                'title'     =>  'Post Types',
                'callback'  =>  array( $this->ctax_callbacks, 'checkboxPostTypeField' ),
                'page'      =>  'jameedium_ctax',
                'section'   =>  'jameedium_ctax_index',
                'args'      =>  array(
                        'label_for'     =>  'objects',
                        'classes'       =>  'ui-toggle',
                        'option_name'   =>  'jameedium_plugin_ctax'
                    )
            )
        ); 

        $this->settings->addFields( $args );
    }
}