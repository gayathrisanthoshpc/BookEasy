<?php
$title = 'Contact'; require 'includes/db.php'; require 'includes/functions.php';
$errors = []; $success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) $errors['form'] = 'Invalid session. Please reload and try again.';
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? ''); $msg = trim($_POST['message'] ?? '');
    if (strlen($name) < 2 || strlen($name) > 80) $errors['name'] = 'Name must be 2-80 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email address.';
    if (strlen($subject) < 3 || strlen($subject) > 120) $errors['subject'] = 'Subject must be 3-120 characters.';
    if (strlen($msg) < 10 || strlen($msg) > 1000) $errors['message'] = 'Message must be 10-1000 characters.';
    if (!$errors) {
        $pdo->prepare("INSERT INTO messages (name,email,subject,message) VALUES (?,?,?,?)")->execute([$name,$email,$subject,$msg]);
        $success = true; $_POST = [];
    }
}
require 'includes/header.php'; ?>
<h1>Contact Us</h1>
<?php if ($success): ?><div class="alert ok">Message sent. We will get back to you soon.</div><?php endif; ?>
<?php if (isset($errors['form'])): ?><div class="alert bad"><?= e($errors['form']) ?></div><?php endif; ?>
<form method="post" novalidate class="form">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <label>Name <input name="name" value="<?= old('name') ?>"><?= err($errors,'name') ?></label>
  <label>Email <input type="email" name="email" value="<?= old('email') ?>"><?= err($errors,'email') ?></label>
  <label>Subject <input name="subject" value="<?= old('subject') ?>"><?= err($errors,'subject') ?></label>
  <label>Message <textarea name="message" rows="5"><?= old('message') ?></textarea><?= err($errors,'message') ?></label>
  <button class="btn" type="submit">Send Message</button>
</form>
<?php require 'includes/footer.php'; ?>
