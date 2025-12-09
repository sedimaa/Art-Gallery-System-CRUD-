<?php require_once __DIR__ . '/lib/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Galleria d'Arte | Admin Login</title>
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="login">
    <div class="login-container">
      <h1>Admin Login</h1>

      <?php if (isset($_GET['err'])): ?>
        <p class="login-error">
          <?php
            if ($_GET['err'] === 'missing') echo "Please enter both username and password.";
            elseif ($_GET['err'] === 'invalid') echo "Invalid username or password.";
            else echo "Something went wrong.";
          ?>
        </p>
      <?php endif; ?>

      <form action="auth/login.php" method="post">
        <input id="username" name="username" type="text" placeholder="Username" required>
        <input id="password" name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>

      <p><a href="index.html">← Back to Home</a></p>
      
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Galleria d'Arte. All Rights Reserved.</p>
  </footer>
</body>
</html>
