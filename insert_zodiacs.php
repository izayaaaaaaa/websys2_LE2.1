<?php
// Database connection setup
$host = "your_db_host";
$dbname = "your_db_name";
$username = "your_db_username";
$password = "your_db_password";

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Read data from Zodiac.txt file and insert into the zodiacs table
  $file = fopen('Zodiac.txt', 'r');
  while ($line = fgets($file)) {
    $details = explode(';', $line);
    $zodiacSign = trim($details[0]);
    $symbol = trim($details[1]);
    $startDate = trim($details[2]);
    $endDate = trim($details[3]);
    
    // Prepare and execute the INSERT query
    $stmt = $conn->prepare("INSERT INTO zodiacs (zodiac_sign, symbol, start_date, end_date) 
    VALUES (:zodiac_sign, :symbol, :start_date, :end_date)");
    
    $stmt->bindParam(':zodiac_sign', $zodiacSign);
    $stmt->bindParam(':symbol', $symbol);
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    
    $stmt->execute();
  }
  fclose($file);
  
  echo "Zodiac data inserted successfully!";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
