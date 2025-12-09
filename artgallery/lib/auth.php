<?php

function start_session(): void {
  if (session_status() === PHP_SESSION_NONE) {
    session_name('galleria_sid');
    session_start();
  }
}

function login_user(string $username, string $password): bool {
  start_session();

  $ADMIN_USER = 'admin';
  $ADMIN_PASS = 'kylehaha';

  if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = $username;
    return true;
  }
  return false;
}

function require_login(): void {
  start_session();
  if (empty($_SESSION['user_id'])) {
    header('Location: /artgallery/login.php');
    exit;
  }
}

function logout_user(): void { 
  start_session(); 
  $_SESSION = []; 
  session_destroy(); }
