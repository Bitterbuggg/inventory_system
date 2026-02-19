<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Workflow Operations</h1>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<p><strong>Role:</strong> <?= esc($user['role_name'] ?? 'Unknown') ?></p>
<ul>
    <li><a href="<?= site_url('workflow/purchase-request') ?>">Create Purchase Request</a></li>
    <li><a href="<?= site_url('workflow/purchase-order') ?>">Create Purchase Order</a></li>
    <li><a href="<?= site_url('workflow/receiving-convert') ?>">Convert Receiving</a></li>
    <li><a href="<?= site_url('workflow/issuance') ?>">Create Issuance</a></li>
</ul>
<p><a href="<?= site_url('dashboard') ?>">Back to Dashboard</a></p>
<?= $this->endSection() ?>
