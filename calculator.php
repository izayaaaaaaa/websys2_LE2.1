<!-- calculator.php -->
<?php
  require 'config.php';

  // Check if the user is already logged in
  if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
  }

  // Your Person and Zodiac classes implementation here
  class Person {
    public $firstName;
    public $lastName;
    public $birthday;
    public $zodiac;
    
    function __construct($firstName, $lastName, $birthday) {
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->birthday = $birthday;
      $this->zodiac = new Zodiac($birthday);
    }
    
    public function getFullName() {
      return $this->firstName . ' ' . $this->lastName;
    }
    
    // get zodiac index
    public function getZodiacIndex() {
      return $this->zodiac->zodiacIndex;
    }
    
    // get zodiac sign
    public function getZodiacSign() {
      return $this->zodiac->zodiacSign;
    }
    
    // get zodiac compatibility
    public function getZodiacCompatibility($zodiac1, $zodiac2) {
      return $this->zodiac->ComputeZodiacCompatibility($zodiac1, $zodiac2);
    }
  }
  
  class Zodiac {
    public $zodiacSign;
    public $symbol;
    public $startDate;
    public $endDate;
    public $zodiacIndex;
    
    public function __construct($date) {
      $date = explode('-', $date); // Store stripped strings to variable $date
      $month = $date[1];
      $day = $date[2];
      $year = $date[0];
      
      // classify zodiac sign of the user 
      if(($month == 3 && $day > 20) || ($month == 4 && $day < 20)){
        $this->zodiacSign = "ARIES";
        $this->zodiacIndex = 0;
      } elseif(($month == 4 && $day > 19) || ($month == 5 && $day < 21)){
        $this->zodiacSign = "TAURUS";
        $this->zodiacIndex = 3;
      } elseif(($month == 5 && $day > 20) || ($month == 6 && $day < 22)){
        $this->zodiacSign = "GEMINI";
        $this->zodiacIndex = 6;
      } elseif(($month == 6 && $day > 21) || ($month == 7 && $day < 23)){
        $this->zodiacSign = "CANCER";
        $this->zodiacIndex = 9;
      } elseif(($month == 7 && $day > 22) || ($month == 8 && $day < 23)){
        $this->zodiacSign = "LEO";
        $this->zodiacIndex = 1;
      } elseif(($month == 8 && $day > 22) || ($month == 9 && $day < 23)){
        $this->zodiacSign = "VIRGO";
        $this->zodiacIndex = 4;
      } elseif(($month == 9 && $day > 22) || ($month == 10 && $day < 24)){
        $this->zodiacSign = "LIBRA";
        $this->zodiacIndex = 7;
      } elseif(($month == 10 && $day > 23) || ($month == 11 && $day < 22)){
        $this->zodiacSign = "SCORPIO";
        $this->zodiacIndex = 10;
      } elseif(($month == 11 && $day > 21) || ($month == 12 && $day < 22)){
        $this->zodiacSign = "SAGITTARIUS";
        $this->zodiacIndex = 2;
      } elseif(($month == 12 && $day > 21) || ($month == 1 && $day < 20)){
        $this->zodiacSign = "CAPRICORN";
        $this->zodiacIndex = 5;
      } elseif(($month == 1 && $day > 19) || ($month == 2 && $day < 19)){
        $this->zodiacSign = "AQUARIUS";
        $this->zodiacIndex = 8;
      } elseif(($month == 2 && $day > 18) || ($month == 3 && $day < 21)){
        $this->zodiacSign = "PISCES";
        $this->zodiacIndex = 11;
      }
      
      $file = fopen('Zodiac.txt', 'r');
      while ($line = fgets($file)){
        $details = explode(';', $line);
        if($details[0] == $this->zodiacSign){
          $this->symbol = $details[1];
          $this->startDate = $details[2];
          $this->endDate = $details[3];
        }
      }
      fclose($file);
    }
    
    function ComputeZodiacCompatibility($zodiac1, $zodiac2) {
      // 0 = Great Match, 1 = Favorable, 2 = Not Favorable
      // To multidimensional array to assess compatibility from Aries to Pisces 
      $compatibility = array(
        array('0','0','0','2','2','2','0','0','0','2','2','1'),
        array('0','0','0','2','2','2','0','0','0','1','1','1'),
        array('0','0','0','2','2','2','0','0','0','1','1','1'),
        array('2','1','2','0','0','0','2','1','2','0','0','0'),
        array('2','1','2','0','0','0','2','2','1','0','0','1'),
        array('2','1','2','0','0','0','2','1','2','0','0','0'),
        array('0','0','1','2','1','1','0','0','0','2','2','2'),
        array('1','0','0','1','2','2','0','0','0','2','2','1'),
        array('0','0','0','2','2','2','0','0','0','2','1','1'),
        array('2','1','1','0','0','0','2','2','2','0','0','0'),
        array('1','1','2','0','0','0','2','2','2','0','0','0'),
        array('1','1','1','0','1','0','2','2','2','0','0','0')
      );
      return $compatibility[$zodiac1][$zodiac2]; // Provide the indicated compatibility of two Zodiac signs assessed
    }
  } // zodiac class

  function getCommonLetters($string1, $string2) {
    $commonLettersArray = '';
    
    foreach (count_chars($string1, 1) as $letter => $frequency) {
      if (strpos($string2, chr($letter)) !== false) {
        $commonLettersArray .= chr($letter) . ' ';
      }
    }
    
    return rtrim($commonLettersArray);
  }
  
  function countCommonLetters($string1, $string2) {
    $commonLetters = getCommonLetters($string1, $string2);
    $commonLettersArray = explode(' ', $commonLetters);
    $countString1 = 0;
    $countString2 = 0;
    
    foreach ($commonLettersArray as $letter) {
      if (!empty($letter)) {
        $countString1 += substr_count($string1, $letter);
        $countString2 += substr_count($string2, $letter);
      }
    }
    
    return array($countString1, $countString2);
  }
  
  function getCompatibility($counts) {
    $countString1 = $counts[0];
    $countString2 = $counts[1];
    
    $remainder = ($countString1 + $countString2) % 6;
    
    $flames = ['S', 'F', 'L', 'A', 'M', 'E'];
    $compatibility = $flames[$remainder];
    
    return $compatibility;
  }
  
  function getRelationshipLabel($compatibility) {
    switch ($compatibility) {
      case 'E':
        return 'Engaged';
      case 'F':
        return 'Friends';
      case 'L':
        return 'Lovers';
      case 'A':
        return 'Anger';
      case 'M':
        return 'Married';
      case 'S':
        return 'Soulmates';
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personOne = new Person($_POST['nameOneFirstName'],$_POST['nameOneLastName'],$_POST['birthday1']);
    $personTwo = new Person($_POST['nameTwoFirstName'],$_POST['nameTwoLastName'],$_POST['birthday2']);
    
    // concatenate first and last names
    $nameOne = $personOne->GetFullName();
    $nameTwo = $personTwo->GetFullName();
    // remove special characters and spacings
    $nameOne = preg_replace('/[^A-Za-z0-9\-]/', '', $nameOne);
    $nameTwo = preg_replace('/[^A-Za-z0-9\-]/', '', $nameTwo);
    // convert to lowercase
    $nameOne = strtolower($nameOne);
    $nameTwo = strtolower($nameTwo);
    
    $commonLetters = getCommonLetters($nameOne, $nameTwo);
    $counts = countCommonLetters($nameOne, $nameTwo);
    $countString1 = $counts[0];
    $countString2 = $counts[1];
    $compatibility = getCompatibility($counts);
    if ($compatibility == 'F') {
      $compatibilityString = "Friends";
    } else if ($compatibility == 'L') {
      $compatibilityString = "Lovers";
    } else if ($compatibility == 'A') {
      $compatibilityString = "Anger";
    } else if ($compatibility == 'M') {
      $compatibilityString = "Married";  
    } else if ($compatibility == 'E') {
      $compatibilityString = "Engaged";
    } else {
      $compatibilityString = "Soulmates";
    }
    
    $zodiacOne = $personOne->GetZodiacSign();
    $zodiacTwo = $personTwo->GetZodiacSign();
    $zodiacIndexOne = $personOne->GetZodiacIndex();
    $zodiacIndexTwo = $personTwo->GetZodiacIndex();
    $zodiacCompatibility = $personOne->getZodiacCompatibility($zodiacIndexOne, $zodiacIndexTwo);
    if ($zodiacCompatibility == '0'){
      $zodiacCompatibility = "Great Match";
    } elseif($zodiacCompatibility == '2'){
      $zodiacCompatibility = "Not Favorable";
    } else {
      $zodiacCompatibility = "Favorable Match";
    }
    
    echo '<div style="margin-bottom: 10px;">';
    echo '<span style="font-weight: bold;">Your name:</span> ' . $personOne->GetFullName() . '<br>';
    echo '<span style="font-weight: bold;">Crush\'s name:</span> ' . $personTwo->GetFullName() . '<br>';
    echo '</div>';
    
    echo '<p><span style="font-weight: bold;">Note:</span> Their common letters are: ' . $commonLetters . '</p>';
    echo '<p><span style="font-weight: bold;">' . $personOne->GetFullName() . '</span> has ' . $countString1 . ' common letters, ';
    echo '<span style="font-weight: bold;">' . $personTwo->GetFullName() . '</span> has ' . $countString2 . ' common letters.</p>';
    
    echo '<p><span style="font-weight: bold;">Zodiac Sign 1:</span> ' . $zodiacOne . '<br>';
    echo '<span style="font-weight: bold;">Zodiac Sign 2:</span> ' . $zodiacTwo . '</p>';
    
    echo '<p><span style="font-weight: bold;">Compatibility:</span> ' . $compatibilityString . '</p>';
    echo '<p><span style="font-weight: bold;">Zodiac Compatibility:</span> ' . $zodiacCompatibility . '</p>';
  } else {
    echo '
      <form action="" method="POST">
      <label for="nameOneFirstName">Your First Name:</label>
      <input type="text" name="nameOneFirstName" id="nameOneFirstName" required><br>
      <label for="nameOneLastName">Your Last Name:</label>
      <input type="text" name="nameOneLastName" id="nameOneLastName" required><br>
      <label for="birthday1">Your Birthday:</label>
      <input type="date" name="birthday1" id="birthday1" required><br>
      
      <label for="nameTwoFirstName">Crush\'s First Name:</label>
      <input type="text" name="nameTwoFirstName" id="nameTwoFirstName" required><br>
      <label for="nameTwoLastName">Crush\'s Last Name:</label>
      <input type="text" name="nameTwoLastName" id="nameTwoLastName" required><br>
      <label for="birthday2">Crush\'s Birthday:</label>
      <input type="date" name="birthday2" id="birthday2" required><br>
      
      <input type="submit" value="Submit">
      </form>
    ';
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        font-family: Arial, sans-serif;
      }

      form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f5f5f5;
      }

      form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }

      form input[type="text"],
      form input[type="date"] {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      form input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        background-color: #4caf50;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
      }

      .results {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f9f9f9;
      }

      .results h2 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 20px;
      }

      .results p {
        margin: 0;
        padding-bottom: 5px;
        font-size: 16px;
      }

      .results hr {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #ccc;
      }

      .compatibility {
        font-weight: bold;
        font-size: 18px;
      }

      .compatibility.great-match {
        color: #4caf50;
      }

      .compatibility.favorable-match {
        color: #ff9800;
      }

      .compatibility.not-favorable-match {
        color: #f44336;
      }
    </style>
  </head>
  <body>
  </body>
</html>