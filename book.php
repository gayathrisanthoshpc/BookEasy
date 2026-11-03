<?php
$title = 'Book Appointment'; require 'includes/db.php'; require 'includes/functions.php';
$services = $pdo->query("SELECT id, name FROM services ORDER BY id")->fetchAll();
$slots = ['09:00','10:00','11:00','12:00','14:00','15:00','16:00'];
$errors = []; $success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $errors['form'] = 'Invalid session. Please reload and try again.'; }
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $sid   = (int)($_POST['service_id'] ?? 0);
    $date  = $_POST['appt_date'] ?? '';
    $slot  = $_POST['time_slot'] ?? '';
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '' || !preg_match("/^[A-Za-z .'-]{2,80}$/", $name)) $errors['name'] = 'Enter a valid name (letters only, 2-80 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    if (!preg_match('/^[0-9+ -]{10,15}$/', $phone)) $errors['phone'] = 'Enter a valid phone number (10-15 digits).';
    if (!in_array($sid, array_column($services, 'id'))) $errors['service_id'] = 'Please select a service.';
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) $errors['appt_date'] = 'Choose a valid date.';
    elseif ($date < date('Y-m-d')) $errors['appt_date'] = 'Date cannot be in the past.';
    elseif ($d->format('w') == 0) $errors['appt_date'] = 'We are closed on Sundays.';
    if (!in_array($slot, $slots)) $errors['time_slot'] = 'Please select a time slot.';
    if (strlen($notes) > 500) $errors['notes'] = 'Notes must be under 500 characters.';

    if (!$errors) {
        try {
            $st = $pdo->prepare("INSERT INTO appointments (name,email,phone,service_id,appt_date,time_slot,notes) VALUES (?,?,?,?,?,?,?)");
            $st->execute([$name, $email, $phone, $sid, $date, $slot, $notes ?: null]);
            $success = true; $_POST = [];
        } catch (PDOException $ex) {
            if ($ex->getCode() == 23000) $errors['time_slot'] = 'That slot is already booked. Please choose another.';
            else $errors['form'] = 'Something went wrong. Please try again.';
        }
    }
}
require 'includes/header.php';
$pre = (int)($_POST['service_id'] ?? $_GET['service'] ?? 0);
?>
<h1>Book an Appointment</h1>
<?php if ($success): ?><div class="alert ok">Thank you! Your appointment request has been received.</div><?php endif; ?>
<?php if (isset($errors['form'])): ?><div class="alert bad"><?= e($errors['form']) ?></div><?php endif; ?>
<form method="post" id="bookForm" novalidate class="form">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <label>Full Name <input name="name" value="<?= old('name') ?>" required><?= err($errors,'name') ?></label>
  <label>Email <input type="email" name="email" value="<?= old('email') ?>" required><?= err($errors,'email') ?></label>
  <label>Phone <input name="phone" value="<?= old('phone') ?>" required><?= err($errors,'phone') ?></label>
  <label>Service
    <select name="service_id" required><option value="">-- Select --</option>
      <?php foreach ($services as $s): ?><option value="<?= $s['id'] ?>" <?= $pre==$s['id']?'selected':'' ?>><?= e($s['name']) ?></option><?php endforeach; ?>
    </select><?= err($errors,'service_id') ?></label>
  <label>Date <input type="date" name="appt_date" min="<?= date('Y-m-d') ?>" value="<?= old('appt_date') ?>" required><?= err($errors,'appt_date') ?></label>
  <label>Time Slot
    <select name="time_slot" required><option value="">-- Select --</option>
      <?php foreach ($slots as $t): ?><option value="<?= $t ?>" <?= ($_POST['time_slot'] ?? '')===$t?'selected':'' ?>><?= $t ?></option><?php endforeach; ?>
    </select><?= err($errors,'time_slot') ?></label>
  <label>Notes (optional) <textarea name="notes" rows="3"><?= old('notes') ?></textarea><?= err($errors,'notes') ?></label>
  <button class="btn" type="submit">Submit Booking</button>
</form>
<script>
// Client-side validation (server-side validation above is the real safeguard)
document.getElementById('bookForm').addEventListener('submit', function (e) {
  const f = this, bad = [];
  if (!/^[A-Za-z .'-]{2,80}$/.test(f.name.value.trim())) bad.push('Enter a valid name.');
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.value)) bad.push('Enter a valid email.');
  if (!/^[0-9+ -]{10,15}$/.test(f.phone.value)) bad.push('Enter a valid phone number.');
  if (!f.service_id.value) bad.push('Select a service.');
  if (!f.appt_date.value) bad.push('Choose a date.');
  if (!f.time_slot.value) bad.push('Select a time slot.');
  if (bad.length) { e.preventDefault(); alert(bad.join('\n')); }
});
</script>
<?php require 'includes/footer.php'; ?>
