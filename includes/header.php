<?php require_once __DIR__ . '/functions.php'; $page = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'BookEasy') ?> | BookEasy</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <div class="wrap nav">
    <a class="logo" href="index.php">BookEasy</a>
    <nav>
      <?php foreach (['index.php'=>'Home','services.php'=>'Services','book.php'=>'Book','contact.php'=>'Contact','admin.php'=>'Admin'] as $f=>$l): ?>
        <a href="<?= $f ?>" class="<?= $page===$f?'active':'' ?>"><?= $l ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>
<main class="wrap">
