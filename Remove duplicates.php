<!DOCTYPE html>
<html>
<head>
<title>Duplicate Finder</title>
</head>
<body>
<h2>Enter a list of numbers (comma-separated or a single number):</h2>
<form method="post">
<input type="text" name="number_list" placeholder="e.g. 1,2,2,3 or 122333" size="50"
value="<?php echo isset($_POST['number_list']) ?
htmlspecialchars($_POST['number_list']) : ''; ?>" required>
<br><br>
<input type="submit" value="Check Duplicates">
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$input = $_POST['number_list'];
// If input contains a comma, treat as comma-separated list
if (strpos($input, ',') !== false) {
$numberArray = array_map('trim', explode(',', $input));
// Remove non-numeric entries (optional)
$numberArray = array_filter($numberArray, 'is_numeric');
} else {
// Treat as a single number and split into individual digits
$numberArray = str_split(preg_replace('/\D/', '', $input));
}
// Show Original List
echo "<h3>Original List with Duplicates:</h3>";
echo implode(", ", $numberArray) . "<br><br>";
// Remove duplicates
$uniqueList = array_unique($numberArray);
echo "<h3>List after Removing Duplicates:</h3>";
echo implode(", ", $uniqueList) . "<br><br>";
// Find duplicate elements
$duplicates = [];
$seen = [];
foreach ($numberArray as $value) {
if (in_array($value, $seen)) {
if (!in_array($value, $duplicates)) {
$duplicates[] = $value;
}
} else {
$seen[] = $value;
}
}
echo "<h3>Duplicate Elements:</h3>";
echo empty($duplicates) ? "None" : implode(", ", $duplicates);
}
?>
</body>
</html>