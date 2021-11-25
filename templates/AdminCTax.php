<?php
/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/
?>

<div class="wrap">
<h1><strong>Custom Taxonomies Manager</strong></h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo (!isset($_POST['edit_taxonomy'])) ? 'active' : '' ?>"><a href="#cpt">Your Custom Taxonomies</a></li>
        <li class="<?php echo (isset($_POST['edit_taxonomy'])) ? 'active' : '' ?>"><a href="#add"><?php echo (isset($_POST['edit_taxonomy'])) ? 'Edit' : 'Add' ?> Custom Taxonomies</a></li>
        <li><a href="#export">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="cpt" class="tab-pane <?php echo (!isset($_POST['edit_taxonomy'])) ? 'active' : '' ?>">
            <h3>manage your Tax</h3>

            <?php
            
                $options = get_option('jameedium_plugin_ctax') ?: array();

                echo '<table class="ctax-table"><tr><th>ID</th><th>Singular Name</th><th>Plural Name</th><th class="text-center">Hierarchical</th><th class="text-center">Block Editor</th><th class="text-center">Actions</th></tr>';

                foreach ($options as $option) {
                    $hierarchical = isset($option['hierarchical']) ? '<span style="color: var(--accent-clr-3);" class="dashicons dashicons-yes-alt"></span>' : '<span style="color: var(--accent-clr-2);" class="dashicons dashicons-dismiss"></span>' ;
                    $show_in_rest = isset($option['show_in_rest']) ? '<span style="color: var(--accent-clr-3);" class="dashicons dashicons-yes-alt"></span>' : '<span style="color: var(--accent-clr-2);" class="dashicons dashicons-dismiss"></span>' ;
                    echo "<tr><td>{$option['taxonomy']}</td><td>{$option['singular_name']}</td><td>{$option['plural_name']}</td><td class=\"text-center\">{$hierarchical}</td><td class=\"text-center\">{$show_in_rest}</td><td class=\"text-center\">";

                    echo '<form method="post" action="" class="inline-block">';

                        echo "<input type='hidden' name='edit_taxonomy' value='{$option['taxonomy']}' >";
                        submit_button('Edit' , 'primary button-small' , 'submit' , false);

                    echo '</form>  ';

                    echo '<form method="post" action="options.php" class="inline-block">';

                        settings_fields( 'jameedium_plugin_ctax_settings' );
                        echo "<input type='hidden' name='remove' value='{$option['taxonomy']}' >";
                        submit_button('Delete' , 'button-small' , 'submit' , false , array(
                            'onclick'   =>  'return confirm("Are you sure you want to delete this Taxonomy!!");'
                        ));

                    echo '</form></td></tr>';
                }
                echo '</table>';
			?>

        </div>
        <div id="add" class="tab-pane <?php echo (isset($_POST['edit_taxonomy'])) ? 'active' : '' ?>">
            <h2>Create a new Taxonomy</h2>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'jameedium_plugin_ctax_settings' );
                    do_settings_sections( 'jameedium_ctax' );
                    submit_button();
                ?>
            </form>
        </div>
        <div id="export" class="tab-pane">
            <h3>Export your Taxonomies</h3>
        </div>
    </div>
</div>