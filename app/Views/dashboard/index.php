<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Dashboard</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<p><strong>User:</strong> <?= esc($user['full_name'] ?? '') ?></p>
<p><strong>Role:</strong> <?= esc($user['role_name'] ?? '') ?></p>

<h2>Role Scope</h2>
<ul>
    <?php foreach (($roleSections[$user['role_name']] ?? []) as $section): ?>
        <li><?= esc($section) ?></li>
    <?php endforeach; ?>
</ul>

<p><a href="<?= site_url('logout') ?>">Logout</a></p>
<?= $this->endSection() ?>
