<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "image_db");
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
// Upload logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
$name = $_FILES['image']['name'];
$sql = "INSERT INTO images (image, name) VALUES ('$image', '$name')";
if ($conn->query($sql) === TRUE) {
echo "<p style='color:green;'>Image uploaded successfully!</p>";
} else {
echo "<p style='color:red;'>Upload failed: " . $conn->error . "</p>";
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Upload and Display Images</title>
</head>
<body>
<h2>Upload Image</h2>
<form method="post" enctype="multipart/form-data">
<input type="file" name="image" required><br><br>
<input type="submit" value="Upload">
</form>
<h2>Uploaded Images:</h2>
<?php
// Display images
$result = $conn->query("SELECT id, name, image FROM images");
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong></p>";
echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '"
height="150"/><br><br>';
}
} else {
echo "No images uploaded yet.";
}
$conn->close();
?>
</body>
</html>