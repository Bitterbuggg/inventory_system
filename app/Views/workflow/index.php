<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div>
    <h1>Workflow Operations</h1>
    <p class="text-slate-600 mb-6">Manage your pharmacy procurement and inventory process</p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-6"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-900"><strong>Your Role:</strong> <?= esc($user['role_name'] ?? 'Unknown') ?></p>
    </div>

    <!-- Workflow Operations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Purchase Request Section -->
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">📋 Purchase Request</h2>
            <p class="text-slate-600 text-sm mb-4">Create and manage purchase requests from suppliers</p>
            <div class="space-y-2">
                <a href="<?= site_url('workflow/purchase-request') ?>" class="btn btn-primary w-full">
                    ➕ Create New Request
                </a>
                <a href="<?= site_url('purchase-requests') ?>" class="btn btn-outline w-full">
                    📜 View All Requests
                </a>
            </div>
        </div>

        <!-- Purchase Order Section -->
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">📦 Purchase Order</h2>
            <p class="text-slate-600 text-sm mb-4">Create and manage purchase orders from approved requests</p>
            <div class="space-y-2">
                <a href="<?= site_url('workflow/purchase-order') ?>" class="btn btn-primary w-full">
                    ➕ Create New Order
                </a>
                <a href="<?= site_url('purchase-orders') ?>" class="btn btn-outline w-full">
                    📜 View All Orders
                </a>
            </div>
        </div>

        <!-- Receiving Section -->
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">📥 Convert Receiving</h2>
            <p class="text-slate-600 text-sm mb-4">Process incoming goods and update inventory</p>
            <a href="<?= site_url('workflow/receiving-convert') ?>" class="btn btn-primary w-full">
                ✓ Process Receiving
            </a>
        </div>

        <!-- Issuance Section -->
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">📤 Issuance</h2>
            <p class="text-slate-600 text-sm mb-4">Issue inventory items and track stock movements</p>
            <a href="<?= site_url('workflow/issuance') ?>" class="btn btn-primary w-full">
                ✓ Create Issuance
            </a>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex justify-center">
        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline">← Back to Dashboard</a>
    </div>
</div>
<?= $this->endSection() ?>
