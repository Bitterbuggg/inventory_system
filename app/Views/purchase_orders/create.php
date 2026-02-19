<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl">
    <h1>Create Purchase Order</h1>
    <p class="text-slate-600 mb-6">Create a new purchase order</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <form action="<?= site_url('purchase-orders') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="supplier_id" class="form-label">Supplier ID</label>
                    <input type="number" id="supplier_id" name="supplier_id" value="<?= esc(old('supplier_id')) ?>" class="form-input" placeholder="e.g., 1" required>
                </div>

                <div class="form-group">
                    <label for="created_by" class="form-label">Created By (User ID)</label>
                    <input type="number" id="created_by" name="created_by" value="<?= esc(old('created_by')) ?>" class="form-input" placeholder="e.g., 1" required>
                </div>

                <div class="form-group">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" id="order_date" name="order_date" value="<?= esc(old('order_date') ?: date('Y-m-d')) ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="expected_delivery_date" class="form-label">Expected Delivery</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="<?= esc(old('expected_delivery_date')) ?>" class="form-input">
                </div>

                <div class="form-group">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="number" step="0.01" id="total_amount" name="total_amount" value="<?= esc(old('total_amount') ?: '0.00') ?>" class="form-input" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label for="terms" class="form-label">Payment Terms</label>
                    <input type="text" id="terms" name="terms" value="<?= esc(old('terms')) ?>" class="form-input" placeholder="Net 30">
                </div>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" class="form-textarea" rows="3" placeholder="Add any special instructions..."><?= esc(old('notes')) ?></textarea>
            </div>

            <div class="form-group">
                <label for="items_json" class="form-label">Items (JSON)</label>
                <p class="text-xs text-slate-600 mb-2">Format: [{"product_id":1, "quantity":10, "received_qty":0, "unit_cost":11.75, "line_total":117.50}]</p>
                <textarea id="items_json" name="items_json" class="form-textarea font-mono text-sm" rows="8" required><?= esc(old('items_json') ?: '[{"product_id":1,"quantity":10,"received_qty":0,"unit_cost":11.75,"line_total":117.50}]') ?></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Create Order</button>
                <a href="<?= site_url('purchase-orders') ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
