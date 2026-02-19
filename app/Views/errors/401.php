<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <div class="card border-red-200 bg-red-50">
            <div class="text-center mb-6">
                <h1 class="text-6xl font-bold text-red-900 mb-2">401</h1>
                <h2 class="text-2xl font-semibold text-red-800">Authentication Required</h2>
            </div>

            <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded-lg">
                <p class="text-red-900 text-center">
                    <?= esc($message ?? 'You must be logged in to access this page.') ?>
                </p>
            </div>

            <div class="flex gap-3">
                <a href="<?= site_url('login') ?>" class="btn btn-primary flex-1">Sign In</a>
                <a href="<?= site_url('/') ?>" class="btn btn-secondary flex-1">Back Home</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
