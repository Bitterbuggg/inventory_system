<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pharmacy Inventory System') ?></title>
    <?php
    $manifestPath = FCPATH . 'build/.vite/manifest.json';
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest['resources/js/app.js'])) {
            $entry = $manifest['resources/js/app.js'];
            // Include CSS files
            if (isset($entry['css'])) {
                foreach ($entry['css'] as $css) {
                    echo '<link rel="stylesheet" href="' . base_url('build/' . $css) . '">' . "\n";
                }
            }
        }
    }
    ?>
</head>
<body class="bg-slate-50">
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= site_url('/') ?>" class="navbar-brand">
                        💊 Pharmacy Inventory
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <?php if (session()->get('auth_user')): ?>
                        <span class="text-sm text-slate-600">
                            Welcome, <strong><?= esc(session()->get('auth_user')['full_name'] ?? 'User') ?></strong>
                        </span>
                        <a href="<?= site_url('dashboard') ?>" class="nav-link">Dashboard</a>
                        <a href="<?= site_url('workflow') ?>" class="nav-link">Workflow</a>
                        <a href="<?= site_url('inventory') ?>" class="nav-link">Inventory</a>
                        <a href="<?= site_url('logout') ?>" class="btn btn-small btn-secondary">Logout</a>
                    <?php else: ?>
                        <a href="<?= site_url('login') ?>" class="btn btn-small btn-primary">Login</a>
                        <a href="<?= site_url('signup') ?>" class="btn btn-small btn-outline">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✓</span>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg">✕</span>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-slate-600">
            <p>&copy; <?= date('Y') ?> Pharmacy Inventory Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
