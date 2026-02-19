<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl">
    <h1>Edit Purchase Order</h1>
    <p class="text-slate-600 mb-6">Update order #<?= esc($record['po_no']) ?></p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <form action="<?= site_url('purchase-orders/' . $record['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="supplier_id" class="form-label">Supplier ID</label>
                    <input type="number" id="supplier_id" name="supplier_id" value="<?= esc(old('supplier_id') ?: $record['supplier_id']) ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="created_by" class="form-label">Created By (User ID)</label>
                    <input type="number" id="created_by" name="created_by" value="<?= esc(old('created_by') ?: $record['created_by']) ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="draft" <?= (old('status') ?: $record['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="approved" <?= (old('status') ?: $record['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="received" <?= (old('status') ?: $record['status']) === 'received' ? 'selected' : '' ?>>Received</option>
                        <option value="cancelled" <?= (old('status') ?: $record['status']) === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" id="order_date" name="order_date" value="<?= esc(old('order_date') ?: $record['order_date']) ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="expected_delivery_date" class="form-label">Expected Delivery</label>
                    <input type="date" id="expected_delivery_date" name="expected_delivery_date" value="<?= esc(old('expected_delivery_date') ?: ($record['expected_delivery_date'] ?? '')) ?>" class="form-input">
                </div>

                <div class="form-group">
                    <label for="total_amount" class="form-label">Total Amount</label>
                    <input type="number" step="0.01" id="total_amount" name="total_amount" value="<?= esc(old('total_amount') ?: ($record['total_amount'] ?? '0.00')) ?>" class="form-input" required>
                </div>

                <div class="form-group md:col-span-2">
                    <label for="terms" class="form-label">Payment Terms</label>
                    <input type="text" id="terms" name="terms" value="<?= esc(old('terms') ?: ($record['terms'] ?? '')) ?>" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" class="form-textarea" rows="3"><?= esc(old('notes') ?: ($record['notes'] ?? '')) ?></textarea>
            </div>

            <div class="form-group">
                <label for="items_json" class="form-label">Items (JSON)</label>
                <textarea id="items_json" name="items_json" class="form-textarea font-mono text-sm" rows="8" required><?= esc(old('items_json') ?: $itemsJson) ?></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <a href="<?= site_url('purchase-orders/' . $record['id']) ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
