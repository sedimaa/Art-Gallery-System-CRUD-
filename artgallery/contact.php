<?php
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/auth.php';

$pdo = db();
$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name === '' || $email === '' || $message === '') {
    $error = 'Please fill in all fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $message])) {
      $success = 'Your message has been sent. Thank you!';
      $_POST = [];
    } else {
      $error = 'An error occurred while sending your message.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galleria d'Arte | Contact</title>
  <link rel="stylesheet" href="style.css">
</head>
  <body class="contact-page">
    <header>
      <nav class="navigation">
        <ul class="nav-links">
          <li><a href="index.html">Home</a></li>
          <li><a href="gallery.php">Gallery</a></li>
          <li><a href="featured.html">Featured</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="contact.php" class="active">Contact</a></li>
          <li><a href="login.php" class="admin-link"><img src="assets/admin-btn.png" alt="" class="admin-nav"></a></li>
        </ul>
      </nav>
    </header>

    <div class="contacts">
      <div class="contact">
        <h1>Contact Us</h1>
        <p>We’d love to hear from you</p>

        <?php if ($success): ?>
          <p class="contact-success"><?= $success ?></p>
        <?php elseif ($error): ?>
          <p class="contact-error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" action="">
          <div class="form-group">
            <input type="text" id="name" name="name" required placeholder="Enter Your Full name"
                  value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"> <br><br>

            <input type="email" id="email" name="email" required placeholder="Enter Your Email"
                  value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br><br>

            <textarea name="message" id="message" rows="4" required placeholder="Write your message here..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea><br><br>
            <button type="submit">Send Message</button>
          </div>

          <div class="contact-links">
            <a href="#"><img src="assets/facebook_logo.png" alt=""></a>
            <a href="#"><img src="assets/instagram_logo.png" alt=""></a>
            <a href="#"><img src="assets/tiktok_logo.png" alt=""></a>
          </div>
        </form>
      </div>
    </div>

    <footer>
      <p>&copy; 2025 Galleria d'Arte. All Rights Reserved.</p>
    </footer>
  </body>
</html>
