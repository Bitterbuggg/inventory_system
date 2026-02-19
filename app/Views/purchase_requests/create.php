<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl">
    <h1>Create Purchase Request</h1>
    <p class="text-slate-600 mb-6">Add a new purchase request with items</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <form action="<?= site_url('purchase-requests') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="requested_by" class="form-label">Requested By (User ID)</label>
                <input type="number" id="requested_by" name="requested_by" value="<?= esc(old('requested_by')) ?>" class="form-input" placeholder="e.g., 1" required>
            </div>

            <div class="form-group">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-textarea" rows="3" placeholder="Add any additional notes..."><?= esc(old('remarks')) ?></textarea>
            </div>

            <div class="form-group">
                <label for="items_json" class="form-label">Items (JSON)</label>
                <p class="text-xs text-slate-600 mb-2">Format: [{"product_id":1, "requested_qty":10, "unit_cost_estimate":12.50}]</p>
                <textarea id="items_json" name="items_json" class="form-textarea font-mono text-sm" rows="8" required><?= esc(old('items_json') ?: '[{"product_id":1,"requested_qty":10,"unit_cost_estimate":12.50}]') ?></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Save Request</button>
                <a href="<?= site_url('purchase-requests') ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
