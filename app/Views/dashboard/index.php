<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div>
    <h1>Welcome to Your Dashboard</h1>
    <p class="text-slate-600 mb-6">Manage your pharmacy inventory efficiently</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-6"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- User Profile Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">👤 Your Profile</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-slate-600">Full Name</p>
                    <p class="text-lg font-medium text-slate-900"><?= esc($user['full_name'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Role</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="badge badge-primary"><?= esc($user['role_name'] ?? 'User') ?></span>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Email</p>
                    <p class="text-sm font-mono text-slate-700"><?= esc($user['email'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>

        <!-- Role Permissions Card -->
        <div class="card">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">🔐 Access Permissions</h2>
            <div class="space-y-2">
                <?php if (empty($roleSections[$user['role_name']] ?? [])): ?>
                    <p class="text-sm text-slate-600">Contact administrator for permissions</p>
                <?php else: ?>
                    <?php foreach ($roleSections[$user['role_name']] ?? [] as $section): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-green-600">✓</span>
                            <span class="text-sm text-slate-700"><?= esc($section) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">⚡ Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php if (in_array(($user['role_name'] ?? ''), ['Admin', 'Employee'], true)): ?>
                <a href="<?= site_url('workflow') ?>" class="p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors text-left">
                    <h3 class="font-semibold text-blue-900">Workflow Operations</h3>
                    <p class="text-sm text-blue-700 mt-1">Manage purchase requests and orders</p>
                </a>
                <a href="<?= site_url('purchase-requests') ?>" class="p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors text-left">
                    <h3 class="font-semibold text-purple-900">Purchase Requests</h3>
                    <p class="text-sm text-purple-700 mt-1">View and manage PR list</p>
                </a>
                <a href="<?= site_url('purchase-orders') ?>" class="p-4 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors text-left">
                    <h3 class="font-semibold text-indigo-900">Purchase Orders</h3>
                    <p class="text-sm text-indigo-700 mt-1">View and manage PO list</p>
                </a>
            <?php else: ?>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                    <p class="text-slate-600 text-sm">No operations available for your role</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
