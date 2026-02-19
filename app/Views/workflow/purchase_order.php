<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<h1>Create Purchase Order</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= site_url('workflow/purchase-order') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="supplier_id">Supplier ID</label>
        <input type="number" id="supplier_id" name="supplier_id" value="<?= esc(old('supplier_id')) ?>" required>
    </div>
    <div class="form-group">
        <label for="created_by">Created By (User ID)</label>
        <input type="number" id="created_by" name="created_by" value="<?= esc(old('created_by')) ?>" required>
    </div>
    <div class="form-group">
        <label for="order_date">Order Date</label>
        <input type="date" id="order_date" name="order_date" value="<?= esc(old('order_date') ?: date('Y-m-d')) ?>" required>
    </div>
    <div class="form-group">
        <label for="expected_delivery_date">Expected Delivery Date</label>
        <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="<?= esc(old('expected_delivery_date')) ?>">
    </div>
    <div class="form-group">
        <label for="total_amount">Total Amount</label>
        <input type="number" step="0.01" id="total_amount" name="total_amount" value="<?= esc(old('total_amount') ?: '0.00') ?>">
    </div>
    <div class="form-group">
        <label for="terms">Terms</label>
        <input type="text" id="terms" name="terms" value="<?= esc(old('terms')) ?>">
    </div>
    <div class="form-group">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes"><?= esc(old('notes')) ?></textarea>
    </div>
    <div class="form-group">
        <label for="items_json">Items JSON</label>
        <textarea id="items_json" name="items_json" rows="6" required><?= esc(old('items_json') ?: '[{"product_id":1,"quantity":10,"unit_cost":12.50,"line_total":125.00}]') ?></textarea>
    </div>
    <button type="submit">Submit Purchase Order</button>
</form>
<p><a href="<?= site_url('workflow') ?>">Back</a></p>
<?= $this->endSection() ?>
