<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl">
    <h1>Edit Purchase Request</h1>
    <p class="text-slate-600 mb-6">Update request #<?= esc($record['request_no']) ?></p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <form action="<?= site_url('purchase-requests/' . $record['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="requested_by" class="form-label">Requested By (User ID)</label>
                <input type="number" id="requested_by" name="requested_by" value="<?= esc(old('requested_by') ?: $record['requested_by']) ?>" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="draft" <?= (old('status') ?: $record['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="approved" <?= (old('status') ?: $record['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= (old('status') ?: $record['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea id="remarks" name="remarks" class="form-textarea" rows="3"><?= esc(old('remarks') ?: ($record['remarks'] ?? '')) ?></textarea>
            </div>

            <div class="form-group">
                <label for="items_json" class="form-label">Items (JSON)</label>
                <textarea id="items_json" name="items_json" class="form-textarea font-mono text-sm" rows="8" required><?= esc(old('items_json') ?: $itemsJson) ?></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update Request</button>
                <a href="<?= site_url('purchase-requests/' . $record['id']) ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
