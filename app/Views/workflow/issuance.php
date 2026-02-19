<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Create Issuance</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= site_url('workflow/issuance') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="issued_by">Issued By (User ID)</label>
        <input type="number" id="issued_by" name="issued_by" value="<?= esc(old('issued_by')) ?>" required>
    </div>
    <div class="form-group">
        <label for="issued_to">Issued To</label>
        <input type="text" id="issued_to" name="issued_to" value="<?= esc(old('issued_to')) ?>" required>
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes"><?= esc(old('notes')) ?></textarea>
    </div>
    <div class="form-group">
        <label for="items_json">Items JSON</label>
        <textarea id="items_json" name="items_json" rows="6" required><?= esc(old('items_json') ?: '[{"product_id":1,"quantity":2}]') ?></textarea>
    </div>
    <button type="submit">Submit Issuance</button>
</form>
<p><a href="<?= site_url('workflow') ?>">Back</a></p>
<?= $this->endSection() ?>
