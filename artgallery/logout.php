<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/config.php';
logout_user();

header('Location: ' . '/artgallery' . '/login.php', true, 303);
exit;