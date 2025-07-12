<?php
// /templates/admin/dashboard.php
if (!defined('ABSPATH')) exit;

?>
<div class="wrap">
    <h1><span class="dashicons dashicons-chart-line"></span> Affiliate Dashboard</h1>
    <p>Welcome to Garo Affiliate Pro Admin Panel.</p>

    <div class="garo-stats">
        <?php
        global $wpdb;
        $aff_table = $wpdb->prefix . 'garo_aff_affiliates';
        $payout_table = $wpdb->prefix . 'garo_aff_payouts';
        $commission_table = $wpdb->prefix . 'garo_aff_commissions';

        $total_affiliates = $wpdb->get_var("SELECT COUNT(*) FROM $aff_table");
        $total_commission = $wpdb->get_var("SELECT SUM(amount) FROM $commission_table");
        $total_payouts = $wpdb->get_var("SELECT SUM(amount) FROM $payout_table");
        ?>
        <ul>
            <li><strong>Total Affiliates:</strong> <?php echo (int) $total_affiliates; ?></li>
            <li><strong>Total Commissions:</strong> KSh <?php echo number_format((float) $total_commission, 2); ?></li>
            <li><strong>Total Paid Out:</strong> KSh <?php echo number_format((float) $total_payouts, 2); ?></li>
        </ul>
    </div>
</div>