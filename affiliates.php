<?php
// /templates/admin/affiliates.php
if (!defined('ABSPATH')) exit;

?>
<div class="wrap">
    <h1><span class="dashicons dashicons-admin-users"></span> Affiliates</h1>
    <p>List of all registered affiliates:</p>
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Referral Code</th>
                <th>Joined</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table = $wpdb->prefix . 'garo_aff_affiliates';
            $results = $wpdb->get_results("SELECT * FROM $table ORDER BY joined DESC LIMIT 50");

            if ($results) {
                foreach ($results as $row) {
                    $user = get_user_by('id', $row->user_id);
                    echo '<tr>';
                    echo '<td>' . esc_html($user->display_name) . '</td>';
                    echo '<td>' . esc_html($user->user_email) . '</td>';
                    echo '<td>' . esc_html($row->referral_code) . '</td>';
                    echo '<td>' . esc_html($row->joined) . '</td>';
                    echo '<td>' . esc_html($row->status) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No affiliates found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
