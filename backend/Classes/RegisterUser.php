<?php



class Register
{
  private $pdo;
  private $err = [];

  // Constructor to initialize the PDO connection
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  //is email valid
  private function isEmail($data)
  {

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    if (!(preg_match($regex, $data))) {
      return false;
    }
    return true;
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
    $specialChars = '@!#$%^&*()-=+[{]};:\'",<.>/?\\|';
    if (strpbrk($data, $specialChars) !== false) {
      return true;
    }
  }






  // Function to check if the username already exists
  private function userExists($username)
  {
    $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    return $stmt->fetchColumn() > 0;
  }

  // Function to check if the username already exists
  private function emailExists($email)
  {
    $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0;
  }


  // Function to register a new user
  public function registerUser($username, $password, $email, $passwordRepeat)
  {
    $result = [];
    // Check if the username is already taken
    if ($this->userExists($username)) {
      $this->err['username'] = "Username already taken.";
    }
    if ($this->notAlphanumeric($username)) {
      $this->err['username'] = "Username must be alphanumeric";
    }
    if ($this->emailExists($email)) {
      $this->err['email'] = "email already taken.";
    }
    if ($this->notEmpty($username)) {
      $this->err['username'] =  "Please enter name";
    }
    if ($this->notEmpty($email)) {
      $this->err['email'] =  "Please enter email";
    }

    if ($this->notEmpty($password)) {
      $this->err['password'] =  "Please enter password";
    }
    if (strlen($password) > 20 || strlen($password) < 5) {
      $this->err['password'] =  "Pasword must be between 5 to 20 characters";
    }
    if ($this->notAlphanumeric($password)) {
      $this->err['password'] =  "Password must be alphanumeric";
    }
    if ($password != $passwordRepeat) {
      $this->err['password'] = "Password Don't matches";
    }


    if (!($this->isEmail($email))) {
      $this->err['email'] = "Please enter a valid Email";
    }
    if (count($this->err) == 0) {

      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Prepare SQL statement to insert the new user
      $stmt = $this->pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)');

      // Execute the statement with the provided data
      $result = $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword,
        'email' => $email,
        'role' => "customer",
      ]);
    }
    // Check if the insertion was successful
    if ($result) {
      return 1;
    } else {
      return $this->err;
    }
  }
}
