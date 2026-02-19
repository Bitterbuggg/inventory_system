<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <div class="card border-yellow-200 bg-yellow-50">
            <div class="text-center mb-6">
                <h1 class="text-6xl font-bold text-yellow-900 mb-2">404</h1>
                <h2 class="text-2xl font-semibold text-yellow-800">Page Not Found</h2>
            </div>

            <div class="mb-6 p-4 bg-yellow-100 border border-yellow-300 rounded-lg">
                <p class="text-yellow-900 text-center">
                    <?= esc($message ?? 'The page you are looking for does not exist.') ?>
                </p>
            </div>

            <div class="flex gap-3">
                <a href="<?= site_url('/') ?>" class="btn btn-primary flex-1">Go Home</a>
                <a href="javascript:history.back()" class="btn btn-secondary flex-1">Go Back</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
