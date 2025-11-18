<?php
$data = [
["id" => 1, "name" => "John", "age" => 25],
["id" => 2, "name" => "Alice", "age" => 30],
["id" => 3, "name" => "Srinivas", "age" => 50],
["id" => 4, "name" => "Saatvik", "age" => 50],
["id" => 5, "name" => "Varun", "age" => 28],
];
// Initialize variables
$searchResults = [];
$query = "";
// Process search
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$query = trim($_POST['query']);
$lowerQuery = strtolower($query);
foreach ($data as $item) {
$idMatch = is_numeric($query) && $item['id'] == $query;
$ageMatch = is_numeric($query) && $item['age'] == $query;
$nameMatch = strpos(strtolower($item['name']), $lowerQuery) !== false;
if ($idMatch || $ageMatch || $nameMatch) {
$searchResults[] = $item;
}
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Accurate Search</title>
</head>
<body>
<h2>Search by ID, Name, or Age</h2>
<form method="post">
<input type="text" name="query" placeholder="Enter ID, Name or Age" value="<?=
htmlspecialchars($query) ?>" required>
<input type="submit" value="Search">
</form>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
<h3>Search Results:</h3>
<?php if (empty($searchResults)): ?>
<p>No results found.</p>
<?php else: ?>
<ul>
<?php foreach ($searchResults as $res): ?>
<li>ID: <?= $res['id'] ?>, Name: <?= htmlspecialchars($res['name']) ?>, Age:
<?= $res['age'] ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php endif; ?>
</body>
</html>