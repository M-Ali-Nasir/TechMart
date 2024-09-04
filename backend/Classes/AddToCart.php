<?php


class AddToCart
{
  private $pdo;
  private $err = [];

  // Constructor to initialize the PDO connection
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }







  // Function to check if the username already exists
  private function productExists($product_id, $user_id)
  {
    $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM cart WHERE product_id = :product_id AND user_id=:user_id');
    $stmt->execute([
      'product_id' => $product_id,
      'user_id' => $user_id,
    ]);
    return $stmt->fetchColumn() > 0;
  }




  // Function to register a new user
  public function addToCart($product_id, $user_id, $quantity, $price)
  {
    $result = [];
    // Check if the username is already taken

    if (isset($_SESSION['user'])) {

      if ($this->productExists($product_id, $user_id)) {



        $stmt = $this->pdo->prepare('SELECT * FROM cart WHERE product_id = :product_id AND user_id = :user_id LIMIT 1');
        $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id]);

        // Fetch the cart data
        $cart = $stmt->fetch();
        $cart_id = $cart['id'];
        $newQuantity = $quantity + $cart['quantity'];
        // Prepare SQL statement to insert the new user
        $stmt = $this->pdo->prepare('UPDATE cart SET quantity = :quantity WHERE id = :id');

        // Execute the statement with the provided data
        $result = $stmt->execute([
          'quantity' => $newQuantity,
          'id' => $cart_id,
        ]);
      } else {
        // Prepare SQL statement to insert the new user
        $stmt = $this->pdo->prepare('INSERT INTO cart (product_id, user_id, quantity, price) VALUES (:product_id, :user_id, :quantity, :price)');

        // Execute the statement with the provided data
        $result = $stmt->execute([
          'product_id' => $product_id,
          'user_id' => $user_id,
          'quantity' => $quantity,
          'price' => $price,
        ]);
      }
    } else {
      $this->err['addtocart'] = "something went wrong";
    }

    // Check if the insertion was successful
    if ($result) {
      return 1;
    } else {
      return $this->err;
    }
  }
}
