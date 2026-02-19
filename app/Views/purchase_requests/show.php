<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1>Purchase Request Details</h1>
            <p class="text-slate-600 text-sm">Request #<?= esc($record['request_no']) ?></p>
        </div>
        <a href="<?= site_url('purchase-requests/' . $record['id'] . '/edit') ?>" class="btn btn-primary">✎ Edit</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-6"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <h3 class="font-semibold text-slate-900 mb-4">Request Information</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Request No</p>
                    <p class="font-mono font-bold text-blue-600"><?= esc($record['request_no']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Status</p>
                    <span class="badge badge-primary"><?= esc($record['status']) ?></span>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Requested By</p>
                    <p class="font-medium"><?= esc($record['requested_by_name'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Request Date</p>
                    <p class="text-sm"><?= esc($record['requested_at'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-slate-900 mb-4">Remarks</h3>
            <p class="text-slate-700 text-sm"><?= esc($record['remarks'] ?? '(No remarks)') ?></p>
        </div>
    </div>

    <div class="card">
        <h3 class="font-semibold text-slate-900 mb-4">Items</h3>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Requested Qty</th>
                        <th>Approved Qty</th>
                        <th>Unit Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($record['items'])): ?>
                        <tr><td colspan="4" class="text-center text-slate-600 py-4">No items</td></tr>
                    <?php else: ?>
                        <?php foreach ($record['items'] as $item): ?>
                            <tr>
                                <td class="text-sm"><?= esc(($item['sku'] ?? '') . ' - ' . ($item['brand_name'] ?? '')) ?></td>
                                <td class="font-semibold"><?= esc($item['requested_qty']) ?></td>
                                <td><?= esc($item['approved_qty'] ?? '-') ?></td>
                                <td class="text-right">$<?= number_format($item['unit_cost_estimate'] ?? 0, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="<?= site_url('purchase-requests') ?>" class="btn btn-outline">← Back to List</a>
    </div>
</div>
<?= $this->endSection() ?>
