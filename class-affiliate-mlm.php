<?php
/**
 * Garo Affiliate Pro - MLM / 2-Level Referral Logic
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_MLM {

    public static function init() {
        add_action('user_register', [__CLASS__, 'store_referrer'], 10, 1);
    }

    /**
     * Store recruiter ID when someone signs up via referral
     */
    public static function store_referrer($user_id) {
        if (!$user_id || !isset($_COOKIE['garo_affiliate_ref'])) return;

        $referrer_id = absint($_COOKIE['garo_affiliate_ref']);

        if ($referrer_id && $referrer_id !== $user_id) {
            // Avoid circular loops
            $existing = get_user_meta($user_id, 'garo_referred_by', true);
            if (empty($existing)) {
                update_user_meta($user_id, 'garo_referred_by', $referrer_id);

                // Optional: add to referrer's downline list
                $downline = get_user_meta($referrer_id, 'garo_downline', true);
                $downline = is_array($downline) ? $downline : [];
                $downline[] = $user_id;
                update_user_meta($referrer_id, 'garo_downline', array_unique($downline));
            }
        }
    }

    /**
     * Helper to get someone's referrer
     */
    public static function get_referrer($user_id) {
        return absint(get_user_meta($user_id, 'garo_referred_by', true));
    }

    /**
     * Helper to get someone's downlines
     */
    public static function get_downline($user_id) {
        $list = get_user_meta($user_id, 'garo_downline', true);
        return is_array($list) ? $list : [];
    }

    /**
     * Check if a user is self-referred
     */
    public static function is_self_referred($user_id) {
        return (isset($_COOKIE['garo_affiliate_ref']) && absint($_COOKIE['garo_affiliate_ref']) === $user_id);
    }
}
