<?php
class Cart
{
  private $pdo;

  // Constructor to initialize the PDO connection
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }





  // Function to verify the user's credentials
  public function showCart($user_id)
  {



    // Prepare SQL statement to retrieve the user's data
    $stmt = $this->pdo->prepare('SELECT * FROM cart WHERE user_id = :userId');
    $stmt->execute(['userId' => $user_id]);

    // Fetch the user data
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($cart) {

      return $cart;
    }
  }

  // Function to verify the user's credentials
  public function getCartItems($user_id)
  {



    // Prepare SQL statement to retrieve the user's data
    $stmt = $this->pdo->prepare('SELECT * FROM cart WHERE user_id = :userId');
    $stmt->execute(['userId' => $user_id]);

    // Fetch the user data
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($cart) {

      $items = [];

      foreach ($cart as $item) {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :Id');
        $stmt->execute(['Id' => $item['product_id']]);

        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $product = $product[0];


        $stmt = $this->pdo->prepare('SELECT name FROM categories WHERE id = :Id');
        $stmt->execute(['Id' => $product['category_id']]);


        $catName = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $product['category'] = $catName[0]['name'];

        $product['quantity'] = $item['quantity'];
        $product['cartId'] = $item['id'];
        //$comb = ['product' => $product[0], 'quantity' => $item['quantity']];

        array_push($items, $product);
      }
      return $items;
    }
  }

  public function removeFromCart($CartId)
  {
    $sql = "DELETE FROM cart WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $CartId);
    return $stmt->execute();
  }
}
