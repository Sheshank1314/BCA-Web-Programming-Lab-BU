<?php
// form.php — Single-file CAPTCHA example with styling to match screenshot
session_start();

// Message to show after submit
$message = "";

// Handle POST submission first (compare to the captcha stored in session)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_input = isset($_POST['captcha_input']) ? trim($_POST['captcha_input']) : '';

    if (!isset($_SESSION['captcha'])) {
        $message = "<p class='err'>Session expired or CAPTCHA missing. Please try again.</p>";
    } elseif ($user_input === '') {
        $message = "<p class='err'>Please enter the CAPTCHA code.</p>";
    } else {
        // Case-insensitive compare
        if (strtoupper($user_input) === strtoupper($_SESSION['captcha'])) {
            $message = "<p class='ok'>Correct! You passed the CAPTCHA.</p>";
            unset($_SESSION['captcha']); // clear after success
        } else {
            $message = "<p class='err'>Wrong CAPTCHA. Try again.</p>";
        }
    }
}

// Generate a new captcha code for the image (will be shown on next form render)
$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captcha_code = '';
$len = strlen($chars);
for ($i = 0; $i < 5; $i++) {
    // random_int is cryptographically secure and available in PHP 7+
    $captcha_code .= $chars[random_int(0, $len - 1)];
}
$_SESSION['captcha'] = $captcha_code;

// Create PNG image and convert to base64 (requires GD extension)
$width = 220;
$height = 60;
$captcha_base64 = '';
$img = @imagecreatetruecolor($width, $height);
if ($img !== false) {
    // Colors
    $bg = imagecolorallocate($img, 255, 255, 255);
    $text = imagecolorallocate($img, 0, 0, 0);

    // Fill background
    imagefilledrectangle($img, 0, 0, $width, $height, $bg);

    // Add a few faint lines for basic obfuscation
    for ($i = 0; $i < 4; $i++) {
        $lc = imagecolorallocate($img, random_int(180, 230), random_int(180, 230), random_int(180, 230));
        imageline($img, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lc);
    }

    // Use built-in font but draw each char with slight vertical jitter
    $font = 5; // built-in font size (1-5)
    $x = 12;
    for ($i = 0; $i < strlen($captcha_code); $i++) {
        $y = random_int(12, 28);
        imagestring($img, $font, $x, $y, $captcha_code[$i], $text);
        $x += 36;
    }

    ob_start();
    imagepng($img);
    $imgdata = ob_get_clean();
    imagedestroy($img);
    $captcha_base64 = base64_encode($imgdata);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Simple CAPTCHA Form</title>
<style>
    /* Page layout similar to screenshot */
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        color: #111;
    }
    .page {
        max-width: 1000px;     /* screenshot had wide window */
        margin: 18px;
    }
    h1#output-title {
        font-size: 22px;
        font-weight: bold;
        margin: 6px 0 18px 0;
    }

    .box {
        /* keep form area small and left-aligned similar to screenshot */
        width: 560px;
    }

    label, p {
        margin: 6px 0;
    }

    img.captcha {
        display: block;
        margin: 8px 0;
        border: 1px solid #ddd;
        background: #fff;
    }

    input[type="text"] {
        padding: 4px 6px;
        font-size: 13px;
        width: 140px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        padding: 4px 8px;
        margin-left: 6px;
        font-size: 13px;
    }

    .err { color: #c00; margin-top: 12px; }
    .ok  { color: #0a7; margin-top: 12px; }
</style>
</head>
<body>
<div class="page">
    <h1 id="output-title">OUTPUT</h1>

    <div class="box">
        <h3>Simple CAPTCHA Form</h3>

        <form method="post" action="">
            <p>Enter the code:</p>

            <?php if ($captcha_base64 !== ''): ?>
                <img class="captcha" src="data:image/png;base64,<?php echo $captcha_base64; ?>" alt="CAPTCHA Image" width="180" height="45">
            <?php else: ?>
                <p><strong>CAPTCHA unavailable (GD extension may be missing).</strong></p>
            <?php endif; ?>

            <input type="text" name="captcha_input" required>
            <input type="submit" value="Submit">
        </form>

        <!-- show message below form like screenshot -->
        <?php echo $message; ?>
    </div>
</div>
</body>
</html>
