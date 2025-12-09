<?php
require_once __DIR__ . '/../lib/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /artgallery/login.php');
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
  header('Location: /artgallery/login.php?err=missing');
  exit;
}

if (login_user($username, $password)) {
  header('Location: /artgallery/admin.php');
  exit;
}

header('Location: /artgallery/login.php?err=invalid');
exit;

