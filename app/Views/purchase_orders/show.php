<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1>Purchase Order Details</h1>
            <p class="text-slate-600 text-sm">Order #<?= esc($record['po_no']) ?></p>
        </div>
        <a href="<?= site_url('purchase-orders/' . $record['id'] . '/edit') ?>" class="btn btn-primary">✎ Edit</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-6"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Information</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">PO No</p>
                    <p class="font-mono font-bold text-blue-600"><?= esc($record['po_no']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Status</p>
                    <span class="badge badge-primary"><?= esc($record['status']) ?></span>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Supplier</p>
                    <p class="font-medium"><?= esc($record['supplier_name'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Created By</p>
                    <p class="text-sm"><?= esc($record['created_by_name'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Details</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Order Date</p>
                    <p class="text-sm"><?= esc($record['order_date'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Expected Delivery</p>
                    <p class="text-sm"><?= esc($record['expected_delivery_date'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Total Amount</p>
                    <p class="text-lg font-bold text-blue-600">$<?= number_format($record['total_amount'] ?? 0, 2) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 uppercase tracking-wide">Terms</p>
                    <p class="text-sm"><?= esc($record['terms'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <h3 class="font-semibold text-slate-900 mb-4">Notes</h3>
        <p class="text-slate-700 text-sm"><?= esc($record['notes'] ?? '(No notes)') ?></p>
    </div>

    <div class="card">
        <h3 class="font-semibold text-slate-900 mb-4">Order Items</h3>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Received</th>
                        <th>Unit Cost</th>
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($record['items'])): ?>
                        <tr><td colspan="5" class="text-center text-slate-600 py-4">No items</td></tr>
                    <?php else: ?>
                        <?php foreach ($record['items'] as $item): ?>
                            <tr>
                                <td class="text-sm"><?= esc(($item['sku'] ?? '') . ' - ' . ($item['brand_name'] ?? '')) ?></td>
                                <td class="font-semibold"><?= esc($item['quantity']) ?></td>
                                <td><?= esc($item['received_qty'] ?? '0') ?></td>
                                <td class="text-right">$<?= number_format($item['unit_cost'] ?? 0, 2) ?></td>
                                <td class="text-right font-semibold">$<?= number_format($item['line_total'] ?? 0, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="<?= site_url('purchase-orders') ?>" class="btn btn-outline">← Back to List</a>
    </div>
</div>
<?= $this->endSection() ?>
