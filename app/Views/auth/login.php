<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Login</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Sign In</button>
</form>
<p><a href="<?= site_url('signup') ?>">Create account</a></p>
<?= $this->endSection() ?>
