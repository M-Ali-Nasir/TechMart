<?php
include_once('../config/config.php');
class Product
{

  private $pdo;
  private $err = [];

  // Constructor to initialize the PDO connection
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }



  //function to check is empty or not
  private function notEmpty($data)
  {
    if (empty($data)) {
      return true;
    }
    return false;
  }

  private function notAlphanumeric($data)
  {
    $specialChars = '!#$%^&*()-=+[{]};:\'"<>/?\\|';
    if (strpbrk($data, $specialChars) !== false) {
      return true;
    }
  }


  private function removePreviousImage($existingImageName)
  {
    if ($existingImageName) {
      $uploadDir = '../../assets/imgs/products/';

      $existingImagePath = $uploadDir . $existingImageName;

      if (file_exists($existingImagePath)) {
        unlink($existingImagePath);
      }
    }
  }



  private function saveProductImage($file, $productId = 0, $existingImageName = null)
  {
    // Set the upload directory relative to this script's location
    $uploadDir = '../../assets/imgs/products/';

    // Check if the upload directory exists, create it if not
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // Define allowed image types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024;

    // Validate the uploaded file
    if (!in_array($file['type'], $allowedTypes)) {
      $this->err['image'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
    }

    if ($file['size'] > $maxFileSize) {
      $this->err['image'] = "File size exceeds the maximum limit of 5MB.";
    }

    // Extract the file extension
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Generate a unique name for the image
    $newFileName = uniqid('product_' . $productId . '_', true) . '.' . $fileExtension;

    // Set the target file path
    $targetFilePath = $uploadDir . $newFileName;

    // Check if an existing image needs to be deleted
    $this->removePreviousImage($existingImageName);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
      // Return the new file name to save in the database
      unset($_SESSION['imageName']);
      return $newFileName;
    } else {
      // Handle the error if the file could not be moved
      $this->err['image'] = "Failed to upload the image.";
      return null;
    }
  }








  // Function to register a new user
  public function addProduct($name, $description, $price, $category_id, $subcategory_id, $image)
  {
    $result = [];
    // Check if the username is already taken

    if ($this->notEmpty($name)) {
      $this->err['name'] =  "Please enter product name";
    }
    if ($this->notEmpty($description)) {
      $this->err['description'] =  "Please enter product name";
    }
    if ($this->notEmpty($price)) {
      $this->err['price'] =  "Please enter product price";
    }
    if ($price <= 0) {
      $this->err['price'] =  "Please can not be 0 or less than 0";
    }
    if ($this->notEmpty($category_id)) {
      $this->err['category_id'] =  "Please enter product name";
    }
    if ($this->notEmpty($subcategory_id)) {
      $this->err['subcategory_id'] =  "Please enter product name";
    }

    if ($this->notAlphanumeric($name)) {
      $this->err['username'] = "Name must be alphanumeric";
    }

    if ($this->notAlphanumeric($description)) {
      $this->err['description'] = "Description must be alphanumeric";
    }



    if (count($this->err) == 0) {



      $sql = "INSERT INTO products (name, description, price, category_id, subcategory_id, image) 
      VALUES (:name, :description, :price, :category_id, :subcategory_id, :image)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':category_id', $category_id);
      $stmt->bindParam(':subcategory_id', $subcategory_id);




      if (!empty($image)) {
        $imageName = $this->saveProductImage($image);
      } else {
        $imageName = "";
      }
      $stmt->bindParam(':image', $imageName);


      $result =  $stmt->execute();
    }
    // Check if the insertion was successful
    if ($result == 1) {
      return 1;
    } else {
      return $this->err;
    }
  }



  public function editProduct($id, $name, $description, $price, $category_id, $subcategory_id, $image)
  {
    $result = [];
    // Check if the username is already taken

    if ($this->notEmpty($name)) {
      $this->err['name'] =  "Please enter product name";
    }
    if ($this->notEmpty($price)) {
      $this->err['price'] =  "Please enter product price";
    }
    if ($price <= 0) {
      $this->err['price'] =  "Please can not be 0 or less than 0";
    }
    if ($this->notEmpty($description)) {
      $this->err['description'] =  "Please enter Desctiption";
    }
    if ($this->notEmpty($category_id)) {
      $this->err['category'] =  "Please select category";
    }
    if ($this->notEmpty($subcategory_id)) {
      $this->err['subcategory'] =  "Please select Subcategory";
    }

    if ($this->notAlphanumeric($name)) {
      $this->err['name'] = "Name must be alphanumeric";
    }

    if ($this->notAlphanumeric($description)) {
      $this->err['description'] = "Description must be alphanumeric";
    }



    if (count($this->err) == 0) {

      $sql = "UPDATE products 
      SET name = :name, description = :description, image = :image, price = :price, category_id = :category_id, subcategory_id = :subcategory_id 
      WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':category_id', $category_id);
      $stmt->bindParam(':subcategory_id', $subcategory_id);





      if (!empty($image)) {
        $query = $this->pdo->query("SELECT image FROM products WHERE id = $id");
        $previmage = $query->fetch(MYSQLI_ASSOC);

        $previmage = $previmage['image'];
        $imageName = $this->saveProductImage($image, $id, $previmage);
      } else {
        $imageName = "";
      }
      $stmt->bindParam(':image', $imageName);


      $result = $stmt->execute();
    }
    // Check if the insertion was successful
    if ($result == 1) {
      return 1;
    } else {
      return $this->err;
    }
  }

  public function deleteProduct($id)
  {
    $query = $this->pdo->query("SELECT image FROM products WHERE id = $id");
    $image = $query->fetch(MYSQLI_ASSOC);

    $image = $image['image'];

    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $this->removePreviousImage($image);
    return $stmt->execute();
  }
}
