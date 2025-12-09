<?php
require_once __DIR__ . '/lib/db.php';
$pdo = db();

$artworks = $pdo->query("SELECT * FROM artworks ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Galleria d'Arte | Gallery</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
  <body>
    <header>
      <nav class="navigation">
        <ul class="nav-links">
          <li><a href="index.html">Home</a></li>
          <li><a href="gallery.php" class="active">Gallery</a></li>
          <li><a href="featured.html">Featured</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </nav>
    </header>

    <section class="gallery-section">
      <h1>Art Gallery</h1>
      <p class="subtitle">Explore timeless masterpieces from around the world</p>

      <form class="gallery-search" id="gallery-search" onsubmit="event.preventDefault()">
        <label for="gallery-q" class="sr-only">Search artworks</label>
        <input id="gallery-q" type="search" placeholder="Search by title, artist, or genre…" autocomplete="off">
        <button type="submit" aria-label="Search">Search</button>
      </form>

      <div class="art-gallery" id="gallery-grid">
        <?php if (!$artworks): ?>
          <p class="no-artworks">No artworks yet.</p>
        <?php else: ?>
          <?php foreach ($artworks as $a):
            $id      = (int)$a['id'];
            $title   = htmlspecialchars($a['title'] ?? '');
            $artist  = htmlspecialchars($a['artist'] ?? '');
            $year    = htmlspecialchars($a['year'] ?? '');
            $img     = htmlspecialchars($a['image_url'] ?? '');
            $desc    = htmlspecialchars($a['description'] ?? '');
            $genre   = htmlspecialchars($a['genre'] ?? '');
            $anchor  = "art-$id";
          ?>
            <div class="art-item"
                data-title="<?= strtolower($title) ?>"
                data-artist="<?= strtolower($artist) ?>"
                data-genre="<?= strtolower($genre) ?>">
              <a href="#<?= $anchor ?>">
                <img src="<?= $img ?>" alt="<?= $title ?>" loading="lazy">
              </a>
              <h3><?= $title ?></h3>
              <p><?= $artist ?></p>
              <?php if ($year): ?><p><?= $year ?></p><?php endif; ?>
              <?php if ($genre): ?>
                <span class="genre-tag"><?= $genre ?></span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <p class="gallery-empty" style="display:none;">No artworks found.</p>
    </section>

    <?php foreach ($artworks as $a):
      $id    = (int)$a['id'];
      $title = htmlspecialchars($a['title'] ?? '');
      $img   = htmlspecialchars($a['image_url'] ?? '');
      $desc  = trim($a['description'] ?? '');
      $year  = trim($a['year'] ?? '');
      $genre = htmlspecialchars($a['genre'] ?? '');
      $anchor= "art-$id";
      $text  = $desc !== '' ? $desc :
              ($year !== '' ? "Created around $year." : "No description available.");
    ?>
      <div id="<?= $anchor ?>" class="lightbox">
        <a href="#" class="close">&times;</a>
        <img src="<?= $img ?>" alt="<?= $title ?>">
        <p class="description">
          <strong><?= $title ?></strong>
          <?= $year ? " &middot; $year" : "" ?>
          <?= $genre ? " &middot; <em>$genre</em>" : "" ?><br>
          <?= htmlspecialchars($text) ?>
        </p>
      </div>
    <?php endforeach; ?>

    <footer>
      <p>&copy; 2025 Galleria d'Arte. All Rights Reserved.</p>
    </footer>

    <script src="js/searchbar.js"></script>
  </body>
</html>
