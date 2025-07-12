<?php
/**
 * Garo Affiliate Pro - REST API Handler
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_API {

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register all API routes under /wp-json/garo-affiliate/v1/
     */
    public function register_routes() {
        register_rest_route('garo-affiliate/v1', '/dashboard', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_dashboard_data'],
            'permission_callback' => [$this, 'check_auth'],
        ]);

        register_rest_route('garo-affiliate/v1', '/referrals', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_referrals'],
            'permission_callback' => [$this, 'check_auth'],
        ]);

        register_rest_route('garo-affiliate/v1', '/earnings', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_earnings'],
            'permission_callback' => [$this, 'check_auth'],
        ]);
    }

    /**
     * Authenticate current user is an affiliate
     */
    public function check_auth() {
        return is_user_logged_in() && get_user_meta(get_current_user_id(), 'garo_is_affiliate', true);
    }

    /**
     * Return basic affiliate dashboard metrics
     */
    public function get_dashboard_data() {
        $user_id = get_current_user_id();
        $data = [];

        global $wpdb;
        $table = $wpdb->prefix . 'garo_affiliates';

        $stats = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE user_id = %d", $user_id), ARRAY_A);

        $data = [
            'referral_code' => $stats['referral_code'] ?? '',
            'clicks'        => (int) $stats['clicks'],
            'sales'         => (int) $stats['sales_count'],
            'earnings'      => number_format_i18n($stats['total_earned'], 2),
            'pending'       => number_format_i18n($stats['pending_payout'], 2),
        ];

        return rest_ensure_response($data);
    }

    /**
     * Get referral logs for current user
     */
    public function get_referrals() {
        global $wpdb;
        $user_id = get_current_user_id();

        $table = $wpdb->prefix . 'garo_affiliate_clicks';
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE affiliate_id = %d ORDER BY created_at DESC LIMIT 50", $user_id));

        return rest_ensure_response($rows);
    }

    /**
     * Get earnings / commission logs
     */
    public function get_earnings() {
        global $wpdb;
        $user_id = get_current_user_id();

        $table = $wpdb->prefix . 'garo_affiliate_commissions';
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE affiliate_id = %d ORDER BY created_at DESC LIMIT 50", $user_id));

        return rest_ensure_response($rows);
    }
}
