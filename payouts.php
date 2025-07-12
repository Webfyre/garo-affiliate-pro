<?php
// /templates/admin/payouts.php
if (!defined('ABSPATH')) exit;

?>
<div class="wrap">
    <h1><span class="dashicons dashicons-money"></span> Payouts</h1>
    <p>Recent affiliate payouts:</p>
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>Affiliate</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Paid At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table = $wpdb->prefix . 'garo_aff_payouts';
            $results = $wpdb->get_results("SELECT * FROM $table ORDER BY paid_at DESC LIMIT 50");

            if ($results) {
                foreach ($results as $row) {
                    $user = get_user_by('id', $row->affiliate_id);
                    echo '<tr>';
                    echo '<td>' . esc_html($user->display_name) . '</td>';
                    echo '<td>' . esc_html($user->user_email) . '</td>';
                    echo '<td>KSh ' . number_format((float) $row->amount, 2) . '</td>';
                    echo '<td>' . esc_html($row->method) . '</td>';
                    echo '<td>' . esc_html($row->paid_at) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No payouts found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>