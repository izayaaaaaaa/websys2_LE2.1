<!-- prospects.php -->
<?php
  require 'config.php';

  // Check if the user is logged in
  if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
  }

  // Handle form submission for adding a prospect
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $birthday = $_POST['birthday'];
    
    // Perform database insert to store prospect details
    try {
      $stmt = $connect->prepare("INSERT INTO prospects (user_id, first_name, last_name, birthday) 
      VALUES (:user_id, :first_name, :last_name, :birthday)");
      
      $stmt->bindParam(':user_id', $_SESSION['user_id']);
      $stmt->bindParam(':first_name', $firstName);
      $stmt->bindParam(':last_name', $lastName);
      $stmt->bindParam(':birthday', $birthday);
      
      $stmt->execute();
      
      header('Location: prospects.php');
      exit();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  // Fetch user prospects from the database
  $prospects = [];
  try {
    $stmt = $connect->prepare("SELECT * FROM prospects WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    
    $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Your head content here -->
  </head>
  <body>
    <h1>Manage Prospects</h1>
    <form action="" method="POST">
      <label for="firstName">First Name:</label>
      <input type="text" name="firstName" id="firstName" required><br>
      
      <label for="lastName">Last Name:</label>
      <input type="text" name="lastName" id="lastName" required><br>
      
      <label for="birthday">Birthday:</label>
      <input type="date" name="birthday" id="birthday" required><br>

      <input type="submit" value="Add Prospect">
    </form>

    <?php if (count($prospects) > 0) : ?>
      <h2>Prospects List</h2>
      <table>
        <tr>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Birthday</th>
          <th>Zodiac</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        <?php foreach ($prospects as $prospect) : ?>
          <tr>
            <td><?php echo $prospect['first_name']; ?></td>
            <td><?php echo $prospect['last_name']; ?></td>
            <td><?php echo $prospect['birthday']; ?></td>
            <td><?php echo computeZodiacSign($prospect['birthday']); ?></td>
            <td><a href="edit_prospect.php?id=<?php echo $prospect['id']; ?>">Edit</a></td>
            <td><a href="delete_prospect.php?id=<?php echo $prospect['id']; ?>">Delete</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <!-- Add the computeZodiacSign() function here -->
    <?php
      function computeZodiacSign($birthday) {
        // Convert birthday to DateTime object
        $date = new DateTime($birthday);
        $month = (int)$date->format('m');
        $day = (int)$date->format('d');

        // Compute and return the zodiac sign
        // Implement your logic here to compute the zodiac sign based on the month and day
        // Return the zodiac sign as a string
        // Example: return "Leo" if the birthday corresponds to the Leo zodiac sign
        // You can use the same logic you implemented in the Zodiac class of calculator.php
      }
    ?>
  </body>
</html>