<?php
/**
 * Garo Affiliate Pro - Sale Notice Email Template
 * Location: /templates/emails/sale-notice.php
 */

if (!defined('ABSPATH')) exit;

$affiliate_name = isset($data['affiliate_name']) ? esc_html($data['affiliate_name']) : 'Affiliate';
$order_id       = isset($data['order_id']) ? esc_html($data['order_id']) : 'N/A';
$amount         = isset($data['amount']) ? esc_html($data['amount']) : 'N/A';
$commission     = isset($data['commission']) ? esc_html($data['commission']) : 'N/A';
$dashboard_link = site_url('/affiliate-dashboard');

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>🎉 You’ve Earned a Commission!</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      color: #333;
      padding: 20px;
    }
    .container {
      background: #fff;
      border-radius: 8px;
      padding: 25px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .button {
      background-color: #1b8f3c;
      color: white;
      padding: 12px 18px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
      margin-top: 20px;
    }
    .button:hover {
      background-color: #14732f;
    }
    .footer {
      margin-top: 30px;
      font-size: 12px;
      color: #888;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🎉 Congrats <?php echo $affiliate_name; ?>!</h2>
    <p>You just earned a new affiliate commission.</p>

    <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
    <p><strong>Order Amount:</strong> KSh <?php echo $amount; ?></p>
    <p><strong>Your Commission:</strong> KSh <?php echo $commission; ?></p>

    <a href="<?php echo esc_url($dashboard_link); ?>" class="button">View Dashboard</a>

    <div class="footer">
      You’re receiving this email because you’re part of the Garo Affiliate Pro Program.<br>
      Powered by GaroGiftShop.co.ke
    </div>
  </div>
</body>
</html>
