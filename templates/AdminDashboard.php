<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/
?>

<div class="wrap">
    <h1>Jameedium Plugin Dashboard</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#manage">Manage Settings</a></li>
        <li><a href="#updates">Updates</a></li>
        <li><a href="#about">About</a></li>
    </ul>

    <div class="tab-content">
        <div id="manage" class="tab-pane active">
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'jameedium_plugin_settings' );
                    do_settings_sections( 'jameedium_plugin' );
                    submit_button();
                ?>
            </form>
        </div>
        <div id="updates" class="tab-pane"></div>
        <div id="about" class="tab-pane"></div>
    </div>
</div>