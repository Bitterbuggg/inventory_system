<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1>Purchase Requests</h1>
            <p class="text-slate-600 text-sm">Manage all purchase requests</p>
        </div>
        <a href="<?= site_url('purchase-requests/create') ?>" class="btn btn-primary">➕ New Request</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-6"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
        <div class="card">
            <p class="text-center text-slate-600 py-8">No purchase requests found. <a href="<?= site_url('purchase-requests/create') ?>" class="text-blue-600 hover:text-blue-800">Create one</a></p>
        </div>
    <?php else: ?>
        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Request No</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="font-mono text-sm"><?= esc($row['id']) ?></td>
                                <td class="font-semibold text-blue-600"><?= esc($row['request_no']) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = match($row['status']) {
                                            'draft' => 'badge-warning',
                                            'approved' => 'badge-success',
                                            'rejected' => 'badge-danger',
                                            default => 'badge-primary'
                                        };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= esc($row['status']) ?></span>
                                </td>
                                <td><?= esc($row['requested_by_name'] ?? '') ?></td>
                                <td class="text-sm text-slate-600"><?= esc($row['requested_at'] ?? '') ?></td>
                                <td class="flex gap-1">
                                    <a href="<?= site_url('purchase-requests/' . $row['id']) ?>" class="btn btn-small btn-primary" title="View">👁</a>
                                    <a href="<?= site_url('purchase-requests/' . $row['id'] . '/edit') ?>" class="btn btn-small btn-secondary" title="Edit">✎</a>
                                    <form action="<?= site_url('purchase-requests/' . $row['id'] . '/delete') ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-small btn-danger" title="Delete" onclick="return confirm('Delete this request?')">🗑</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?= $pager->links('purchase_requests') ?>
        </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?= site_url('workflow') ?>" class="btn btn-outline">← Back to Workflow</a>
    </div>
</div>
<?= $this->endSection() ?>
