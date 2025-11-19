<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Input Validation</title>
</head>
<body>
<?php
// Initialize variables
$name = $email = $mobile = $age = "";
$nameErr = $emailErr = $mobileErr = $ageErr = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get raw inputs and trim whitespace
    $rawName   = isset($_POST['name']) ? trim($_POST['name']) : '';
    $rawEmail  = isset($_POST['email']) ? trim($_POST['email']) : '';
    $rawMobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    $rawAge    = isset($_POST['age']) ? trim($_POST['age']) : '';

    // Name validation
    if ($rawName === "") {
        $nameErr = "Name is required";
    } else {
        // Optionally validate name characters here
        $name = htmlspecialchars($rawName, ENT_QUOTES, 'UTF-8');
    }

    // Email validation
    if ($rawEmail === "") {
        $emailErr = "Email is required";
    } elseif (!filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = htmlspecialchars($rawEmail, ENT_QUOTES, 'UTF-8');
    }

    // Mobile validation: exactly 10 digits
    if ($rawMobile === "") {
        $mobileErr = "Mobile number is required";
    } elseif (!preg_match('/^[0-9]{10}$/', $rawMobile)) {
        $mobileErr = "Invalid mobile number (must be 10 digits)";
    } else {
        $mobile = htmlspecialchars($rawMobile, ENT_QUOTES, 'UTF-8');
    }

    // Age validation: integer between 1 and 120
    if ($rawAge === "") {
        $ageErr = "Age is required";
    } elseif (!ctype_digit($rawAge)) { // ensures only digits
        $ageErr = "Invalid age (must be a whole number)";
    } else {
        $ageInt = (int)$rawAge;
        if ($ageInt <= 0 || $ageInt > 120) {
            $ageErr = "Invalid age (must be between 1 and 120)";
        } else {
            $age = htmlspecialchars((string)$ageInt, ENT_QUOTES, 'UTF-8');
        }
    }
}
?>
<h2>PHP Form Validation Example</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  Name: <input type="text" name="name" value="<?php echo $name; ?>">
  <span style="color:red;">* <?php echo $nameErr; ?></span>
  <br><br>

  Email: <input type="text" name="email" value="<?php echo $email; ?>">
  <span style="color:red;">* <?php echo $emailErr; ?></span>
  <br><br>

  Mobile: <input type="text" name="mobile" value="<?php echo $mobile; ?>">
  <span style="color:red;">* <?php echo $mobileErr; ?></span>
  <br><br>

  Age: <input type="text" name="age" value="<?php echo $age; ?>">
  <span style="color:red;">* <?php echo $ageErr; ?></span>
  <br><br>

  <input type="submit" name="submit" value="Submit">
</form>

<?php
// Only show submitted data when there are no errors
if ($_SERVER["REQUEST_METHOD"] === "POST" && $nameErr === "" && $emailErr === "" && $mobileErr === "" && $ageErr === "") {
    echo "<h3>Submitted Data:</h3>";
    echo "Name: " . $name . "<br>";
    echo "Email: " . $email . "<br>";
    echo "Mobile: " . $mobile . "<br>";
    echo "Age: " . $age . "<br>";
}
?>
</body>
</html>
