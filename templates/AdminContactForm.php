<?php

/** |==================================|
*   | @package JameediumSitePlugin     |
*   |==================================|
*/

echo '<h1>Activate the contact form</h1>';
?>

<form method="post" action="options.php">
<?php
    settings_fields( 'jameedium_plugin_contact_settings' );
    do_settings_sections( 'jameedium_contact_form' );
    submit_button();
?>
</form>