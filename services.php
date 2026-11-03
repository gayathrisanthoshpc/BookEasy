<?php $title = 'Services'; require 'includes/db.php'; require 'includes/header.php';
$services = $pdo->query("SELECT * FROM services ORDER BY id")->fetchAll(); ?>
<h1>Our Services</h1>
<div class="grid">
<?php foreach ($services as $s): ?>
  <div class="card">
    <h3><?= e($s['name']) ?></h3>
    <p><?= e($s['description']) ?></p>
    <p class="meta"><?= (int)$s['duration_min'] ?> min &middot; &#8377;<?= number_format($s['price'], 2) ?></p>
    <a class="btn small" href="book.php?service=<?= (int)$s['id'] ?>">Book this</a>
  </div>
<?php endforeach; ?>
</div>
<?php require 'includes/footer.php'; ?>
