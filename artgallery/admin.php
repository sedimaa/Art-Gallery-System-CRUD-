<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/db.php';
$cfg = require __DIR__ . '/config/config.php';
require_login();

$pdo = db();
$artworks = $pdo->query("SELECT * FROM artworks ORDER BY id DESC")->fetchAll();
$messages = $pdo->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
$artCount = $pdo->query("SELECT COUNT(*) FROM artworks")->fetchColumn();
$msgCount = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Galleria d'Arte | Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
  <body>
    <header class="admin-header">
      <nav class="navigation">
        <ul class="nav-links">
          <li><a href="#dashboard">Dashboard</a></li>
          <li><a href="#manage">Manage Gallery</a></li>
          <li><a href="#addartwork">Add Artwork</a></li>
          <li><a href="#messages">Messages</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>

    <main class="dashboard" id="dashboard">
      <section class="stats">
        <div class="card">
          <h3>🖼️ <?= $artCount ?> Artworks</h3>
        </div>
        <div class="card">
          <h3>⭐6 Featured</h3>
        </div>
        <div class="card">
          <h3>📬 <?= $msgCount ?> Messages</h3>
        </div>
        <div class="card">
          <h3>👤1 Admin</h3>
        </div>
      </section>
    </main>


    <?php start_session(); ?>
    <section class="admin-welcome" style="text-align:center; margin:1rem 0;">
      <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    </section>

    <main class="dashboard" id="dashboard">
      <section class="manage-gallery" id="manage">
        <h2>Manage Artworks</h2>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Image</th><th>Title</th><th>Artist</th><th>Genre</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($artworks as $a): ?>
              <tr>
                <td>
                  <img src="<?= htmlspecialchars($a['image_url']) ?>"
                      alt="<?= htmlspecialchars($a['title']) ?>"
                      >
                </td>
                <td><?= htmlspecialchars($a['title']) ?></td>
                <td><?= htmlspecialchars($a['artist']) ?></td>
                <td><?= htmlspecialchars($a['genre'] ?? '') ?></td>
                <td>
                  <button class="update-btn"
                    onclick='fillForm(<?= json_encode($a, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                    Update
                  </button>

                  <form action="<?= $cfg['base_path'] ?>/api/artworks.php" method="post" style="display:inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section class="add-artwork" id="addartwork">
        <h2>Add / Update Artwork</h2>
        <form class="add-artwork-form" action="<?= $cfg['base_path'] ?>/api/artworks.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id">

          <div class="form-group">
            <label for="title">Artwork Title</label>
            <input type="text" id="title" name="title" required>
          </div>

          <div class="form-group">
            <label for="artist">Artist Name</label>
            <input type="text" id="artist" name="artist" required>
          </div>

          <div class="form-group">
            <label for="genre">Genre</label>
            <select name="genre" id="genre" required>
              <option value="">Select Genre...</option>
              <option>Realism</option>
              <option>Impressionism</option>
              <option>Renaissance</option>
              <option>Minimalism</option>
              <option>Pop Art</option>
              <option>Modern</option>
              <option>Digital</option>
              <option>Street Art</option>
            </select>
          </div>

          <div class="form-group">
            <label for="year">Year</label>
            <input type="text" id="year" name="year" placeholder="e.g., 1818–1819">
          </div>

          <div class="form-group">
            <label for="image">Image URL (optional)</label>
            <input type="url" id="image" name="image_url" placeholder="https://starrynightXhevabi.com/images?">
          </div>

          <div class="form-group">
            <label for="image_file">Upload Image (JPG/PNG/WebP · max 10MB)</label>
            <input type="file" id="image_file" name="image_file" accept="image/*">
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
          </div>

          <button type="submit" class="submit-btn">Save Artwork</button>
        </form>
      </section>
    </main>

    <section id="messages" class="manage-messages">
      <h2>Contact Messages</h2>
      <?php if (empty($messages)): ?>
        <p class="muted">No messages yet.</p>
      <?php else: ?>
        <div class="table-wrap">
          <table class="messages-table">
            <thead>
              <tr>
                <th>From</th>
                <th>Email</th>
                <th>Message</th>
                <th>Received</th>
                <th style="width:1%;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($messages as $m): ?>
                <tr>
                  <td><?= htmlspecialchars($m['name']) ?></td>
                  <td>
                    <a href="mailto:<?= htmlspecialchars($m['email']) ?>">
                      <?= htmlspecialchars($m['email']) ?>
                    </a>
                  </td>
                  <td class="msg-cell"><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                  <td><?= htmlspecialchars($m['created_at']) ?></td>
                  <td>
                    <form action="<?= $cfg['base_path'] ?>/api/messages.php" method="post" onsubmit="return confirm('Delete this message?');">
                      <input type="hidden" name="_method" value="DELETE">
                      <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
                      <button type="submit" class="delete-btn small">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>


    <footer>
      <p>&copy; 2025 Galleria d'Arte. All Rights Reserved.</p>
    </footer>

    <script src="js/admin.js"></script>
  </body>
</html>
