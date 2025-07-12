<?php
/**
 * Garo Affiliate Pro - Payout Manager
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Payouts {

    public static function init() {
        add_action('admin_post_garo_affiliate_pay_now', [__CLASS__, 'handle_payout_action']);
    }

    /**
     * Get unpaid commissions for user
     */
    public static function get_pending_commissions($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'garo_aff_commissions';

        return $wpdb->get_results($wpdb->prepare("
            SELECT * FROM $table 
            WHERE user_id = %d AND status = 'unpaid'
        ", $user_id));
    }

    /**
     * Get total unpaid amount for a user
     */
    public static function get_user_balance($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'garo_aff_commissions';

        return $wpdb->get_var($wpdb->prepare("
            SELECT SUM(amount) FROM $table 
            WHERE user_id = %d AND status = 'unpaid'
        ", $user_id));
    }

    /**
     * Check if affiliate is eligible for payout
     */
    public static function is_eligible_for_payout($user_id) {
        $threshold = floatval(get_option('garo_aff_minimum_payout', 1000));
        $balance = self::get_user_balance($user_id);
        return $balance && $balance >= $threshold;
    }

    /**
     * Mark all unpaid commissions as paid
     */
    public static function mark_commissions_paid($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'garo_aff_commissions';

        $wpdb->query($wpdb->prepare("
            UPDATE $table 
            SET status = 'paid', paid_at = NOW() 
            WHERE user_id = %d AND status = 'unpaid'
        ", $user_id));
    }

    /**
     * Admin form submission for manual payout
     */
    public static function handle_payout_action() {
        if (!current_user_can('manage_options') || !isset($_GET['user_id'])) {
            wp_die('Access denied.');
        }

        $user_id = intval($_GET['user_id']);
        check_admin_referer('garo_affiliate_pay_' . $user_id);

        if (self::is_eligible_for_payout($user_id)) {
            self::mark_commissions_paid($user_id);
            wp_redirect(admin_url('admin.php?page=garo_affiliates&message=paid'));
            exit;
        } else {
            wp_redirect(admin_url('admin.php?page=garo_affiliates&message=not_eligible'));
            exit;
        }
    }

    /**
     * Optional: get total paid to a user (for history)
     */
    public static function get_total_paid($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'garo_aff_commissions';

        return $wpdb->get_var($wpdb->prepare("
            SELECT SUM(amount) FROM $table 
            WHERE user_id = %d AND status = 'paid'
        ", $user_id));
    }
}
