<!DOCTYPE html>
<html>
<head>
<title>PHP Exception Handling with Form</title>
<style>
body {
font-family: Arial, sans-serif;
background: #f4f4f4;
padding: 30px;
}
.form-box, .result-box {
background: #fff;
padding: 20px;
border: 1px solid #ccc;
box-shadow: 2px 2px 10px #aaa;
max-width: 500px;
margin: auto;
}
h2 {
text-align: center;
}
input[type="text"], input[type="submit"] {
padding: 10px;
margin: 10px 0;
width: 100%;
font-size: 16px;
}
.result-box {
margin-top: 20px;
}
</style>
</head>
<body>
<?php
// Initialize variables to preserve values after submit
$num1 = isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : '';
$num2 = isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : '';
$date_input = isset($_POST['date_input']) ? htmlspecialchars($_POST['date_input']) : '';
?>
<div class="form-box">
<h2>Exception Handling Form</h2>
<form method="post">
<label>Enter numerator:</label>
<input type="text" name="num1" value="<?php echo $num1; ?>" required>
<label>Enter denominator:</label>
<input type="text" name="num2" value="<?php echo $num2; ?>" required>
<label>Enter date (YYYY-MM-DD):</label>
<input type="text" name="date_input" value="<?php echo $date_input; ?>" required>
<input type="submit" name="submit" value="Submit">
</form>
</div>
<?php
if (isset($_POST['submit'])) {
echo '<div class="result-box">';
// i) Division
try {
if (!is_numeric($num1) || !is_numeric($num2)) {
throw new Exception("Division error: Inputs must be numbers.");
}
if ($num2 == 0) {
throw new Exception("Division error: Cannot divide by zero.");
}
$result = $num1 / $num2;
echo "Division result: $num1 / $num2 = $result<br>";
} catch (Exception $e) {
echo $e->getMessage() . "<br>";
}
// ii) Date format
try {
$format = 'Y-m-d';
$d = DateTime::createFromFormat($format, $date_input);
if ($d && $d->format($format) === $date_input) {
echo "The date $date_input is valid.<br>";
} else {
throw new Exception("Date error: Invalid date format.");
}
} catch (Exception $e) {
echo $e->getMessage() . "<br>";
}
echo '</div>';
}
?>
</body>
</html>
