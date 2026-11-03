<?php $title = 'Home'; require 'includes/db.php'; require 'includes/header.php';
$count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(); ?>
<section class="hero">
  <h1>Book your appointment in under a minute</h1>
  <p>Choose a service, pick a date and time, and we will confirm by email.</p>
  <a class="btn" href="book.php">Book Now</a>
  <a class="btn alt" href="services.php">View <?= (int)$count ?> Services</a>
</section>
<section class="grid">
  <div class="card"><h3>1. Choose</h3><p>Pick the service that suits you.</p></div>
  <div class="card"><h3>2. Schedule</h3><p>Select an available date and time slot.</p></div>
  <div class="card"><h3>3. Confirm</h3><p>We review and confirm your booking.</p></div>
</section>
<?php require 'includes/footer.php'; ?>
