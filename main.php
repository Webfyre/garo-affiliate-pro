<?php
/**
 * Garo Affiliate Pro - Dashboard Template
 * Location: /templates/dashboard/main.php
 */

if (!defined('ABSPATH')) exit;

// 🔐 Require login to access dashboard
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$user_id = get_current_user_id();
$affiliate_id = get_user_meta($user_id, 'garo_affiliate_id', true);

// Default stats (these can be filled via AJAX or PHP)
$clicks   = 0;
$sales    = 0;
$earnings = 'KSh 0.00';
$ref_link = site_url('?ref=' . $affiliate_id);
?>

<div class="garo-affiliate-dashboard ajax-enabled">

  <!-- Dashboard Header -->
  <div class="garo-dashboard-header">
    <h2>Welcome, <?php echo esc_html(wp_get_current_user()->display_name); ?> 🎉</h2>
    <p>Your unique referral link:</p>
    <div class="garo-referral-link">
      <input type="text" readonly value="<?php echo esc_url($ref_link); ?>">
      <button class="garo-copy-ref">Copy Link</button>
    </div>
  </div>

  <!-- Dashboard Tabs -->
  <div class="garo-dashboard-tabs">
    <button class="active" data-tab-target="tab-stats">📊 Stats</button>
    <button data-tab-target="tab-referrals">🧑‍🤝‍🧑 Referrals</button>
    <button data-tab-target="tab-earnings">💸 Earnings</button>
    <button data-tab-target="tab-payouts">🏦 Payouts</button>
    <button data-tab-target="tab-settings">⚙️ Settings</button>
  </div>

  <!-- Dashboard Content -->
  <div class="garo-dashboard-content">
    <!-- Stats -->
    <div class="garo-tab-content active" id="tab-stats">
      <h3>📈 Performance Overview</h3>
      <div class="garo-stats-grid">
        <div class="stat-box"><span class="label">Clicks</span><span class="value garo-stat-clicks"><?php echo esc_html($clicks); ?></span></div>
        <div class="stat-box"><span class="label">Sales</span><span class="value garo-stat-sales"><?php echo esc_html($sales); ?></span></div>
        <div class="stat-box"><span class="label">Earnings</span><span class="value garo-stat-earnings"><?php echo esc_html($earnings); ?></span></div>
      </div>
    </div>

    <!-- Referrals -->
    <div class="garo-tab-content" id="tab-referrals">
      <h3>👥 Your Referrals</h3>
      <p>List of users you've referred and their performance.</p>
      <div id="garo-referral-list">
        <p>Loading...</p>
      </div>
    </div>

    <!-- Earnings -->
    <div class="garo-tab-content" id="tab-earnings">
      <h3>💰 Commission Earnings</h3>
      <p>Detailed breakdown of your commissions per sale.</p>
      <div id="garo-earning-list">
        <p>Loading...</p>
      </div>
    </div>

    <!-- Payouts -->
    <div class="garo-tab-content" id="tab-payouts">
      <h3>🏦 Payouts & Withdrawals</h3>
      <p>Minimum payout: <strong>KSh 1,000</strong>. Withdraw once threshold is met.</p>
      <div id="garo-payout-section">
        <p>Loading...</p>
      </div>
    </div>

    <!-- Settings -->
    <div class="garo-tab-content" id="tab-settings">
      <h3>⚙️ Account Settings</h3>
      <p>Update your email, payment method or contact info.</p>
      <div id="garo-settings-form">
        <p>Coming soon...</p>
      </div>
    </div>
  </div>
</div>
