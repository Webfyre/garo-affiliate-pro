<?php
// /templates/admin/settings.php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
    <h1><span class="dashicons dashicons-admin-settings"></span> Garo Affiliate Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('garo_affiliate_settings_group');
        do_settings_sections('garo_affiliate_settings');
        submit_button('Save Settings');
        ?>
    </form>
</div>
