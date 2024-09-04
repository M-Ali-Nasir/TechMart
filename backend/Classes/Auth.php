<?php
//session_start();
class Auth
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


  private function notAlphanumeric($data)
  {
    $specialChars = '@!#$%^&*()-=+[{]};:\'",<.>/?\\|';
    if (strpbrk($data, $specialChars) !== false) {
      return true;
    }
  }

  // Function to verify the user's credentials
  public function loginUser($email, $password)
  {


    if (!($this->isEmail($email))) {
      $this->err['email'] = "Please enter a valid Email";
    }

    if ($this->notAlphanumeric($password)) {
      $this->err['password'] =  "Password must be alphanumeric";
    }
    // Prepare SQL statement to retrieve the user's data
    $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);

    // Fetch the user data
    $user = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($user) {

      if (password_verify($password, $user['password'])) {
        //session_start();
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return 1;
      } else {

        $this->err['password'] =  "Invalid Password.";
      }
    } else {

      $this->err['email'] = "Invalid Email";
    }

    return $this->err;
  }

  // Function to check if the user is logged in
  public function isLoggedIn()
  {

    return isset($_SESSION['user_id']);
  }

  // Function to log out the user
  public function logoutUser()
  {

    session_unset();
    session_destroy();
    return 1;
  }
}
