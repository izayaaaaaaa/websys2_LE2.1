<?php
  require 'config.php';

  // Handle form submission for user registration
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Perform database insert to store user registration details
    try {
      $stmt = $connect->prepare("INSERT INTO users (first_name, last_name, address, email, password) VALUES (:first_name, :last_name, :address, :email, :password)");

      $stmt->bindParam(':first_name', $firstName);
      $stmt->bindParam(':last_name', $lastName);
      $stmt->bindParam(':address', $address);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);

      $stmt->execute();

      header('Location: home.php');
      exit();
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
    <h1>User Registration</h1>
    <form action="" method="POST">
      <label for="firstName">First Name:</label>
      <input type="text" name="firstName" id="firstName" required><br>
      
      <label for="lastName">Last Name:</label>
      <input type="text" name="lastName" id="lastName" required><br>
      
      <label for="address">Address:</label>
      <input type="text" name="address" id="address" required><br>
      
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" required><br>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required><br>

      <input type="submit" value="Register">
    </form>
  </body>
</html>
