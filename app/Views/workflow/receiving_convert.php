<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Convert Receiving</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= site_url('workflow/receiving-convert') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="purchase_order_id">Purchase Order ID</label>
        <input type="number" id="purchase_order_id" name="purchase_order_id" value="<?= esc(old('purchase_order_id')) ?>" required>
    </div>
    <div class="form-group">
        <label for="received_by">Received By (User ID)</label>
        <input type="number" id="received_by" name="received_by" value="<?= esc(old('received_by')) ?>" required>
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes"><?= esc(old('notes')) ?></textarea>
    </div>
    <div class="form-group">
        <label for="items_json">Items JSON</label>
        <textarea id="items_json" name="items_json" rows="6" required><?= esc(old('items_json') ?: '[{"product_id":1,"received_qty":10,"batch_no":"BATCH-001","expiry_date":"2027-12-31","unit_cost":12.50}]') ?></textarea>
    </div>
    <button type="submit">Convert Receiving</button>
</form>
<p><a href="<?= site_url('workflow') ?>">Back</a></p>
<?= $this->endSection() ?>
