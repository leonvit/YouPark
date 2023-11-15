<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'; // Include Stripe PHP library

\Stripe\Stripe::setApiKey('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');

header('Content-Type: application/json');

$paymentIntent = \Stripe\PaymentIntent::create([
  'amount' => 5000, // Amount in cents
  'currency' => 'eur', // Change to your currency
]);

echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
?>
