<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Create Purchase Request</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= site_url('workflow/purchase-request') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="requested_by">Requested By (User ID)</label>
        <input type="number" id="requested_by" name="requested_by" value="<?= esc(old('requested_by')) ?>" required>
    </div>
    <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea id="remarks" name="remarks"><?= esc(old('remarks')) ?></textarea>
    </div>
    <div class="form-group">
        <label for="items_json">Items JSON</label>
        <textarea id="items_json" name="items_json" rows="6" required><?= esc(old('items_json') ?: '[{"product_id":1,"requested_qty":10,"unit_cost_estimate":12.50}]') ?></textarea>
    </div>
    <button type="submit">Submit Purchase Request</button>
</form>
<p><a href="<?= site_url('workflow') ?>">Back</a></p>
<?= $this->endSection() ?>
