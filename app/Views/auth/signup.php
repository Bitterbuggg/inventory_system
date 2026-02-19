<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Signup</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-error">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('signup') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= esc(old('full_name')) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required>
    </div>
    <div class="form-group">
        <label for="role_name">Role</label>
        <select id="role_name" name="role_name" required>
            <option value="">Select a role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= esc($role['name']) ?>" <?= old('role_name') === $role['name'] ? 'selected' : '' ?>>
                    <?= esc($role['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="password_confirm">Confirm Password</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    <button type="submit">Create Account</button>
</form>
<p><a href="<?= site_url('login') ?>">Back to login</a></p>
<?= $this->endSection() ?>
