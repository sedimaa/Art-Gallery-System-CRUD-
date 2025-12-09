<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/auth.php';

$cfg  = require __DIR__ . '/../config/config.php';
$BASE = $cfg['base_path'];
$UPLOAD_DIR = realpath(__DIR__ . '/../uploads');

require_login();
$pdo = db();

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
  $method = strtoupper($_POST['_method']);
}

function back($hash = '#manage') {
  global $BASE;
  header('Location: ' . $BASE . '/admin.php' . $hash);
  exit;
}

function sanitize_filename($name) {
  $name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $name);
  return substr($name, -120);
}
function within_uploads($path) {
  global $UPLOAD_DIR;
  $real = realpath($path);
  return $real && str_starts_with($real, $UPLOAD_DIR);
}

if ($method === 'POST') {
  $id          = (int)($_POST['id'] ?? 0);
  $title       = trim($_POST['title'] ?? '');
  $artist      = trim($_POST['artist'] ?? '');
  $genre       = trim($_POST['genre'] ?? '');     
  $year        = trim($_POST['year'] ?? '');
  $image_url   = trim($_POST['image_url'] ?? '');
  $description = trim($_POST['description'] ?? '');

  if ($title === '' || $artist === '') back('#addartwork');

  $current = null;
  if ($id > 0) {
    $st = $pdo->prepare("SELECT * FROM artworks WHERE id=?");
    $st->execute([$id]);
    $current = $st->fetch();
  }

  if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $tmp  = $_FILES['image_file']['tmp_name'];
    $size = (int)$_FILES['image_file']['size'];
    $type = mime_content_type($tmp) ?: '';

    if ($size > 10 * 1024 * 1024){
      die("<p style='color:red;text-align:center;'>Image exceeds 10 MB. Please upload a smaller file.</p>");
    };
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($type, $allowed, true)) back('#addartwork');

    $ext = match(true) {
      str_contains($type,'jpeg') => '.jpg',
      str_contains($type,'png')  => '.png',
      str_contains($type,'webp') => '.webp',
      default => ''
    };
    $base = pathinfo($_FILES['image_file']['name'], PATHINFO_FILENAME);
    $fname = time() . '_' . bin2hex(random_bytes(4)) . '_' . sanitize_filename($base) . $ext;

    $dest = $UPLOAD_DIR . DIRECTORY_SEPARATOR . $fname;
    if (!move_uploaded_file($tmp, $dest)) back('#addartwork');

    $image_url = 'uploads/' . $fname;

    if ($current && !empty($current['image_url']) && str_starts_with($current['image_url'], 'uploads/')) {
      $old = __DIR__ . '/../' . $current['image_url'];
      if (within_uploads($old) && file_exists($old)) @unlink($old);
    }
  }

  if ($image_url === '') back('#addartwork');

  if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE artworks
      SET title=?, artist=?, genre=?, year=?, image_url=?, description=?
      WHERE id=?");
    $stmt->execute([$title, $artist, $genre, $year, $image_url, $description, $id]);
  } else {
    $stmt = $pdo->prepare("INSERT INTO artworks (title, artist, genre, year, image_url, description)
                           VALUES (?,?,?,?,?,?)");
    $stmt->execute([$title, $artist, $genre, $year, $image_url, $description]);
  }
  back();
}

if ($method === 'DELETE') {
  $id = (int)($_POST['id'] ?? 0);
  if ($id > 0) {
    $st = $pdo->prepare("SELECT image_url FROM artworks WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch();

    $del = $pdo->prepare("DELETE FROM artworks WHERE id=?");
    $del->execute([$id]);

    if ($row && !empty($row['image_url']) && str_starts_with($row['image_url'], 'uploads/')) {
      $old = __DIR__ . '/../' . $row['image_url'];
      if (within_uploads($old) && file_exists($old)) @unlink($old);
    }
  }
  back();
}

if ($method === 'GET') {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($pdo->query("SELECT * FROM artworks ORDER BY id DESC")->fetchAll());
  exit;

}
