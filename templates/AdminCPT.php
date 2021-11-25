<?php
/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/
?>

<div class="wrap">
<h1><strong>Custom Post Type Manager</strong></h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo (!isset($_POST['edit_post'])) ? 'active' : '' ?>"><a href="#cpt">Your Custom Post Type</a></li>
        <li class="<?php echo (isset($_POST['edit_post'])) ? 'active' : '' ?>"><a href="#add"><?php echo (isset($_POST['edit_post'])) ? 'Edit' : 'Add' ?> Custom Post Type</a></li>
        <li><a href="#export">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="cpt" class="tab-pane <?php echo (!isset($_POST['edit_post'])) ? 'active' : '' ?>">
            <h3>manage your CPT</h3>

            <?php
            
                $options = get_option('jameedium_plugin_cpt') ?: array();

                echo '<table class="cpt-table"><tr><th>ID</th><th>Singular Name</th><th>Plural Name</th><th class="text-center">Public</th><th class="text-center">Archive</th><th class="text-center">Actions</th></tr>';

                foreach ($options as $option) {
                    $public = isset($option['public']) ? '<span style="color: var(--accent-clr-3);" class="dashicons dashicons-yes-alt"></span>' : '<span style="color: var(--accent-clr-2);" class="dashicons dashicons-dismiss"></span>' ;
                    $archive = isset($option['has_archive']) ? '<span style="color: var(--accent-clr-3);" class="dashicons dashicons-yes-alt"></span>' : '<span style="color: var(--accent-clr-2);" class="dashicons dashicons-dismiss"></span>' ;
                    echo "<tr><td>{$option['post_type']}</td><td>{$option['singular_name']}</td><td>{$option['plural_name']}</td><td class=\"text-center\">{$public}</td><td class=\"text-center\">{$archive}</td><td class=\"text-center\">";

                    echo '<form method="post" action="" class="inline-block">';

                        echo "<input type='hidden' name='edit_post' value='{$option['post_type']}' >";
                        submit_button('Edit' , 'primary button-small' , 'submit' , false);

                    echo '</form>  ';

                    echo '<form method="post" action="options.php" class="inline-block">';

                        settings_fields( 'jameedium_plugin_cpt_settings' );
                        echo "<input type='hidden' name='remove' value='{$option['post_type']}' >";
                        submit_button('Delete' , 'button-small' , 'submit' , false , array(
                            'onclick'   =>  'return confirm("Are you sure you want to delete this CPT the posts related will not be deleted.");'
                        ));

                    echo '</form></td></tr>';
                }
                echo '</table>';
			?>

        </div>
        <div id="add" class="tab-pane <?php echo (isset($_POST['edit_post'])) ? 'active' : '' ?>">
            <h2>Create a new CPT</h2>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'jameedium_plugin_cpt_settings' );
                    do_settings_sections( 'jameedium_cpt' );
                    submit_button();
                ?>
            </form>
        </div>
        <div id="export" class="tab-pane">
            <h3>Export your CPTs</h3>

            <?php
                $options = get_option('jameedium_plugin_cpt') ?: array();

                foreach ( $options as $option )
                {
                    echo "<h1>The Post: {$option['singular_name']}</h1>";
            ?>
            <pre class="prettyprint">
// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( '<?php echo $option['plural_name']; ?>s', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( '<?php echo $option['singular_name']; ?>', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( '<?php echo $option['plural_name']; ?>s', 'text_domain' ),
		'name_admin_bar'        => __( '<?php echo $option['singular_name']; ?>', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( '<?php echo $option['singular_name']; ?>', 'text_domain' ),
		'description'           => __( '<?php echo $option['singular_name']; ?> Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => false,
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => <?php echo isset($option['public']) ? 'true' : 'false' ?>,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => <?php echo isset($option['has_archive']) ? 'true' : 'false' ?>,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( '<?php echo $option['post_type']; ?>', $args );

}
add_action( 'init', 'custom_post_type', 0 );
</pre>
            <?php 
                } 
            ?>
        </div>
    </div>
</div>