<?php
/**
 * Garo Affiliate Pro - Welcome Email Template
 * Location: /templates/emails/welcome.php
 */

if (!defined('ABSPATH')) exit;

$affiliate_name  = isset($data['affiliate_name']) ? esc_html($data['affiliate_name']) : 'Affiliate';
$dashboard_link  = site_url('/affiliate-dashboard');
$support_email   = get_option('admin_email');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>🎉 Welcome to Garo Affiliate Pro</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f2f2f2;
      padding: 20px;
      color: #333;
    }
    .container {
      background-color: #fff;
      border-radius: 8px;
      padding: 30px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    h2 {
      color: #1b8f3c;
    }
    .button {
      background-color: #1b8f3c;
      color: #fff;
      padding: 12px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      margin-top: 20px;
      display: inline-block;
    }
    .button:hover {
      background-color: #14732f;
    }
    .footer {
      margin-top: 30px;
      font-size: 12px;
      text-align: center;
      color: #888;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Hi <?php echo $affiliate_name; ?>, welcome aboard! 🎉</h2>
    <p>You’ve successfully joined the <strong>Garo Affiliate Pro</strong> program. We’re excited to have you on the team!</p>

    <p>Get started by sharing your referral links and tracking your performance on your dashboard.</p>

    <a class="button" href="<?php echo esc_url($dashboard_link); ?>">Access Your Dashboard</a>

    <p>If you have any questions, feel free to reach out to us at <a href="mailto:<?php echo esc_attr($support_email); ?>"><?php echo esc_html($support_email); ?></a>.</p>

    <div class="footer">
      Powered by GaroGiftShop.co.ke
    </div>
  </div>
</body>
</html>
