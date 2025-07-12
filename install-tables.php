<?php
/**
 * Garo Affiliate Pro - Install Class
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Install {

    public static function install() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix . 'garo_aff_';

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Affiliates Table
        dbDelta("
            CREATE TABLE {$prefix}affiliates (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                referral_code VARCHAR(50) NOT NULL UNIQUE,
                parent_id BIGINT UNSIGNED DEFAULT NULL,
                earnings DECIMAL(10,2) DEFAULT 0,
                tier_level TINYINT DEFAULT 1,
                status VARCHAR(20) DEFAULT 'active',
                joined DATETIME DEFAULT CURRENT_TIMESTAMP,
                KEY user_id (user_id),
                KEY parent_id (parent_id)
            ) $charset_collate;
        ");

        // Clicks Table
        dbDelta("
            CREATE TABLE {$prefix}clicks (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                affiliate_id BIGINT UNSIGNED NOT NULL,
                ip_address VARCHAR(45),
                user_agent TEXT,
                referrer TEXT,
                country VARCHAR(64),
                browser VARCHAR(64),
                device VARCHAR(64),
                clicked_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                KEY affiliate_id (affiliate_id)
            ) $charset_collate;
        ");

        // Sales Table
        dbDelta("
            CREATE TABLE {$prefix}sales (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                order_id BIGINT UNSIGNED NOT NULL,
                affiliate_id BIGINT UNSIGNED NOT NULL,
                commission DECIMAL(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'unpaid',
                sale_amount DECIMAL(10,2),
                paid_at DATETIME NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                KEY order_id (order_id),
                KEY affiliate_id (affiliate_id)
            ) $charset_collate;
        ");

        // Payouts Table
        dbDelta("
            CREATE TABLE {$prefix}payouts (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                affiliate_id BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                method VARCHAR(100),
                mpesa_number VARCHAR(20),
                note TEXT,
                paid_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                KEY affiliate_id (affiliate_id)
            ) $charset_collate;
        ");

        // ✅ Commissions Table
        dbDelta("
            CREATE TABLE {$prefix}commissions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                order_id BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                level TINYINT DEFAULT 1,
                origin_id BIGINT UNSIGNED DEFAULT NULL,
                status VARCHAR(20) DEFAULT 'unpaid',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                KEY user_id (user_id),
                KEY order_id (order_id),
                KEY origin_id (origin_id)
            ) $charset_collate;
        ");
    }
}
