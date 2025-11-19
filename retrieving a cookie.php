<?php
$username = ""; // Default empty
// Handle form submission and set cookie
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["username"])) {
$username = htmlspecialchars($_POST["username"]);
setcookie("username", $username, time() + (86400 * 30)); // 30 days
header("Location: " . $_SERVER["PHP_SELF"]);
exit();
}
// If cookie is set, use it for display and input
if (isset($_COOKIE["username"])) {
$username = $_COOKIE["username"];
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Cookie Example</title>
<style>
body { font-family: Arial; text-align: center; margin-top: 50px; }
.message {
padding: 10px;
font-weight: bold;
width: 300px;
margin: 0 auto 20px auto;
border: 1px solid #ccc;
background-color: #f2f2f2;
}
</style>
</head>
<body>
<?php
if (!empty($username)) {
echo "<div class='message'>Welcome back, " . htmlspecialchars($username) . "!</div>";
} else {
echo "<div class='message'>Welcome, guest! Cookie not set.</div>";
}
?>
<form method="post" action="">
<label>Enter your name:</label><br><br>
<input type="text" name="username" value="<?php echo htmlspecialchars($username);
?>" required><br><br>
<input type="submit" value="Set Cookie">
</form>
</body>
</html>