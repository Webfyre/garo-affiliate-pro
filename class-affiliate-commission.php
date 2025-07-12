<?php
/**
 * Garo Affiliate Pro - Commission Engine
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Commission {

    public static function init() {
        add_action('woocommerce_thankyou', [__CLASS__, 'process_order_commission'], 20);
    }

    /**
     * Process affiliate commission after order is placed
     */
    public static function process_order_commission($order_id) {
        if (!$order_id || !function_exists('wc_get_order')) return;
        $order = wc_get_order($order_id);
        if (!$order || $order->get_status() !== 'processing') return;

        $customer_id = $order->get_user_id();
        if (!$customer_id || Garo_Affiliate_MLM::is_self_referred($customer_id)) return;

        $referrer_id = Garo_Affiliate_MLM::get_referrer($customer_id);
        if (!$referrer_id || $referrer_id === $customer_id) return;

        $total = floatval($order->get_total());
        if ($total <= 0) return;

        $settings = get_option('garo_affiliate_settings', []);
        $base_percent = floatval($settings['commission_rate'] ?? 10); // default 10%
        $super_affiliates = array_map('intval', $settings['super_affiliates'] ?? []);

        // Boost for super affiliates
        if (in_array($referrer_id, $super_affiliates)) {
            $base_percent += floatval($settings['super_boost'] ?? 5); // +5%
        }

        $commission = round(($total * $base_percent) / 100, 2);

        // Store commission
        self::log_commission([
            'order_id'    => $order_id,
            'user_id'     => $referrer_id,
            'amount'      => $commission,
            'level'       => 1,
            'origin_id'   => $customer_id,
        ]);

        // Bonus for referrer’s referrer (level 2)
        $parent_ref = Garo_Affiliate_MLM::get_referrer($referrer_id);
        if ($parent_ref && $parent_ref !== $customer_id) {
            $mlm_percent = floatval($settings['mlm_bonus'] ?? 2); // 2% override
            $mlm_bonus = round(($total * $mlm_percent) / 100, 2);

            self::log_commission([
                'order_id'    => $order_id,
                'user_id'     => $parent_ref,
                'amount'      => $mlm_bonus,
                'level'       => 2,
                'origin_id'   => $customer_id,
            ]);
        }
    }

    /**
     * Log commission to DB
     */
    public static function log_commission($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'garo_aff_commissions';

        $wpdb->insert($table, [
            'user_id'   => intval($data['user_id']),
            'order_id'  => intval($data['order_id']),
            'amount'    => floatval($data['amount']),
            'level'     => intval($data['level']),
            'origin_id' => intval($data['origin_id']),
            'status'    => 'unpaid',
            'created_at'=> current_time('mysql'),
        ]);
    }
}
