<?php
/**
 * Garo Affiliate Pro - Uninstall Cleanup
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('garo_affiliate_settings');
delete_option('garo_affiliate_version');

// Remove custom DB tables
global $wpdb;
$tables = [
    $wpdb->prefix . 'garo_affiliates',
    $wpdb->prefix . 'garo_affiliate_clicks',
    $wpdb->prefix . 'garo_affiliate_sales',
    $wpdb->prefix . 'garo_affiliate_payouts',
    $wpdb->prefix . 'garo_affiliate_referrals'
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS $table");
}
