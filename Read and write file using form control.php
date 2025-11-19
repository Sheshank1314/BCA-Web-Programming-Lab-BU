<!DOCTYPE html>
<html>
<head>
<title>Read and Write File</title>
</head>
<body>
<h2>Write to File</h2>
<form method="post">
Enter Text: <br>
<textarea name="textdata" rows="5" cols="40"><?php
// Retain textarea value after submit
if (isset($_POST['write'])) {
echo htmlspecialchars($_POST['textdata']);
}
?></textarea><br>
Filename: <input type="text" name="writefile" placeholder="example.txt" value="<?php
// Retain filename for write
if (isset($_POST['write'])) {
echo htmlspecialchars($_POST['writefile']);
}
?>"><br><br>
<input type="submit" name="write" value="Write to File">
</form>
<h2>Read from File</h2>
<form method="post">
Filename: <input type="text" name="readfile" placeholder="example.txt" value="<?php
// Retain filename for read
if (isset($_POST['read'])) {
echo htmlspecialchars($_POST['readfile']);
}
?>"><br><br>
<input type="submit" name="read" value="Read File Contents">
</form>
<?php
// Write to file
if (isset($_POST['write'])) {
$filename = trim($_POST['writefile']);
$data = $_POST['textdata'];
if (!empty($filename)) {
file_put_contents($filename, $data);
echo "<br><strong>Data written to file '".htmlspecialchars($filename)."'
successfully.</strong><br>";
} else {
echo "<br><strong>Please enter a filename to write.</strong><br>";
}
}
// Read from file
if (isset($_POST['read'])) {
$filename = trim($_POST['readfile']);
if (!empty($filename)) {
if (file_exists($filename)) {
$content = file_get_contents($filename);
echo "<br><strong>File Content of '".htmlspecialchars($filename)."':</strong><br>";
echo "<pre>" . htmlspecialchars($content) . "</pre>";
} else {
echo "<br><strong>File '".htmlspecialchars($filename)."' not found!</strong><br>";
}
} else {
echo "<br><strong>Please enter a filename to read.</strong><br>";
}
}
?>
</body>
</html>
