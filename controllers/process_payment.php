<?php
// Receive the payment details from frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['status' => 'fail', 'message' => 'No data received']);
    exit;
}

$orderId = $data['id'] ?? 'N/A';
$payerName = $data['payer']['name']['given_name'] ?? 'Unknown';
$payerEmail = $data['payer']['email_address'] ?? 'Unknown';
$amount = $data['purchase_units'][0]['amount']['value'] ?? '0.00';

$log = "Payment Received:\n";
$log .= "Order ID: $orderId\n";
$log .= "Payer: $payerName <$payerEmail>\n";
$log .= "Amount: $amount\n";
$log .= "Timestamp: " . date("Y-m-d H:i:s") . "\n\n";

file_put_contents("paypal_payments_log.txt", $log, FILE_APPEND);

echo json_encode(['status' => 'success']);
?>
