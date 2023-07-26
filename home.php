<?php
  require 'config.php';

  // Check if the user is logged in
  $isLoggedIn = isset($_SESSION['user_id']);

  // Logout functionality
  if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: home.php');
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Your head content here -->
  </head>
  <body>
    <div>
      <h1>Welcome to FLAMES Program</h1>

      <?php if ($isLoggedIn) : ?>
        <!-- Navigation menu for logged-in users -->
        <nav>
          <a href="calculator.php">Calculate Compatibility</a>
          <a href="prospects.php">Manage Prospects</a>
          <a href="home.php?logout=true">Logout</a>
        </nav>
        <p>Welcome, User! You are logged in.</p>
      <?php else : ?>
        <!-- Options for not logged-in users -->
        <p>Login to access the features.</p>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </body>
</html>
