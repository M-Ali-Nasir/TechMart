<?php

require "../../vendor/autoload.php";

class CheckOut
{

  private $pdo;
  private $err = [];

  // Constructor to initialize the PDO connection
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }


  private function convertPKRtoUSD($amount)
  {
    // Implement the conversion logic here
    // For example, using a fixed conversion rate
    $conversionRate = 0.0036; // Example conversion rate, update as needed
    return $amount * $conversionRate;
  }



  public function stripeCheckout($customerId)
  {
    // Fetch customer from database
    $customerQuery = $this->pdo->query("SELECT * FROM users WHERE id = " . intval($customerId));
    $customer = $customerQuery->fetch();

    // Initialize Stripe client
    \Stripe\Stripe::setApiKey("sk_test_51PG1kF2LReVvpXJDMJiMtdhtY2Q0HG3QTnbD0szlNRPjOgVLjQEUUoLdxWT7R8nCy6nKmUbpAmAsAnsUp8Osw9RX001s3KbKls");

    // Fetch cart items for the customer
    $cartItemsQuery = $this->pdo->query("SELECT * FROM cart WHERE user_id = " . intval($customerId));
    $cartItems = $cartItemsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Prepare redirect URL
    //&session_id={CHECKOUT_SESSION_ID}
    $redirectUrl = "http://localhost/TechMart/backend/orderReciept.php?session_id={CHECKOUT_SESSION_ID}";

    // Map cart items to Stripe line items
    $db = $this->pdo;
    $lineItems = array_map(function ($cartItem) use ($db) {
      $productQuery = $this->pdo->query("SELECT * FROM products WHERE id = " . intval($cartItem['product_id']));
      $product = $productQuery->fetch();

      // Currency conversion logic (You need to implement convertPKRtoUSD method)

      $cartItemPrice = $cartItem['price'];
      $price = round($cartItemPrice * 100); // Convert price to cents

      return [
        'price_data' => [
          'currency' => 'usd',
          'unit_amount' => $price,
          'product_data' => [
            'name' => $product['name'],
          ],
        ],
        'quantity' => $cartItem['quantity'],
      ];
    }, $cartItems);

    // Create Stripe Checkout session
    $stripe = new \Stripe\StripeClient("sk_test_51PG1kF2LReVvpXJDMJiMtdhtY2Q0HG3QTnbD0szlNRPjOgVLjQEUUoLdxWT7R8nCy6nKmUbpAmAsAnsUp8Osw9RX001s3KbKls");
    $response = $stripe->checkout->sessions->create([
      'success_url' => $redirectUrl,
      'customer_email' => $customer['email'],
      'payment_method_types' => ['link', 'card'],
      'line_items' => $lineItems,
      'mode' => 'payment',
      'allow_promotion_codes' => false,
    ]);

    // Redirect to the Stripe Checkout page
    header("Location: " . $response['url']);
    exit;
  }
}
