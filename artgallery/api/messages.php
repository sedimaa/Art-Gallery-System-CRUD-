<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';

require_login();
$pdo = db();

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
  $method = strtoupper($_POST['_method']);
}

function back() {
  $url = '/artgallery/admin.php';
  header('Location: ' . $url . '#messages');
  exit;
}

if ($method === 'DELETE') {
  $id = (int)($_POST['id'] ?? 0);
  if ($id > 0) {
    $st = $pdo->prepare('DELETE FROM messages WHERE id=?');
    $st->execute([$id]);
  }
  back();
}
