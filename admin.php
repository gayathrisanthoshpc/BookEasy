<?php
$title = 'Admin'; require 'includes/db.php'; require 'includes/functions.php';
if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }
$loginErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if (csrf_check() && hash_equals(ADMIN_PASS, $_POST['password'])) { session_regenerate_id(true); $_SESSION['admin'] = true; }
    else $loginErr = 'Incorrect password.';
}
if (!empty($_SESSION['admin']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && csrf_check()) {
    $status = $_POST['status']; $id = (int)($_POST['id'] ?? 0);
    if (in_array($status, ['pending','confirmed','cancelled'])) $pdo->prepare("UPDATE appointments SET status=? WHERE id=?")->execute([$status,$id]);
    header('Location: admin.php'); exit;
}
require 'includes/header.php';
if (empty($_SESSION['admin'])): ?>
<h1>Admin Login</h1>
<?php if ($loginErr): ?><div class="alert bad"><?= e($loginErr) ?></div><?php endif; ?>
<form method="post" class="form">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <label>Password <input type="password" name="password" required></label>
  <button class="btn" type="submit">Login</button>
</form>
<?php else:
$appts = $pdo->query("SELECT a.*, s.name AS service FROM appointments a JOIN services s ON s.id=a.service_id ORDER BY a.appt_date, a.time_slot")->fetchAll();
$msgs = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll(); ?>
<h1>Dashboard <a class="btn small alt" href="?logout=1">Logout</a></h1>
<h2>Appointments (<?= count($appts) ?>)</h2>
<div class="scroll"><table>
<tr><th>Date</th><th>Time</th><th>Name</th><th>Contact</th><th>Service</th><th>Status</th><th>Action</th></tr>
<?php foreach ($appts as $a): ?>
<tr><td><?= e($a['appt_date']) ?></td><td><?= e(substr($a['time_slot'],0,5)) ?></td><td><?= e($a['name']) ?></td>
<td><?= e($a['email']) ?><br><?= e($a['phone']) ?></td><td><?= e($a['service']) ?></td>
<td><span class="tag <?= e($a['status']) ?>"><?= e($a['status']) ?></span></td>
<td><form method="post" class="inline"><input type="hidden" name="csrf" value="<?= csrf_token() ?>"><input type="hidden" name="id" value="<?= $a['id'] ?>">
<select name="status"><?php foreach (['pending','confirmed','cancelled'] as $s): ?><option <?= $a['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select>
<button class="btn small">Save</button></form></td></tr>
<?php endforeach; ?></table></div>
<h2>Messages (<?= count($msgs) ?>)</h2>
<?php foreach ($msgs as $m): ?>
<div class="card"><strong><?= e($m['subject']) ?></strong><p class="meta"><?= e($m['name']) ?> &lt;<?= e($m['email']) ?>&gt; &middot; <?= e($m['created_at']) ?></p><p><?= nl2br(e($m['message'])) ?></p></div>
<?php endforeach; endif;
require 'includes/footer.php'; ?>
