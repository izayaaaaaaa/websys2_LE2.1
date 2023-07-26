<?php
  require 'config.php';

  // Check if the user is already logged in
  if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
  }

  // Function to validate login credentials
  function validateLogin($email, $password, $connect) {
    $stmt = $connect->prepare("SELECT id, email, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if ($row && $password === $row['password']) { // Compare the passwords
      return true;
    }
  
    return false;
  }

  // Handle form submission for user login
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
  
    try {
      $user_id = validateLogin($email, $password, $connect); // Get the 'id' returned by the validateLogin function.
  
      if ($user_id !== false) {
        // Login successful, set session variable and redirect to home.php
        $_SESSION['user_id'] = $user_id; // Set the user_id in the session, not just 'id'.
        header('Location: home.php');
        exit();
      } else {
        $loginError = "Invalid email or password";
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Your head content here -->
  </head>
  <body>
    <h1>User Login</h1>
    <form action="" method="POST">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" required><br>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required><br>

      <input type="submit" value="Login">
    </form>
    <?php if (isset($loginError)) : ?>
      <p><?php echo $loginError; ?></p>
    <?php endif; ?>
  </body>
</html>
  