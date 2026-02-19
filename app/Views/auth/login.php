<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <div class="card">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Welcome Back</h1>
                <p class="text-slate-600 text-sm mt-1">Sign in to your account</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error mb-4"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mb-4"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" class="form-input" placeholder="admin@pharmacy.local" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">Sign In</button>
            </form>

            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="text-center text-sm text-slate-600">
                    Don't have an account? <a href="<?= site_url('signup') ?>" class="text-blue-600 hover:text-blue-800 font-medium">Create one</a>
                </p>
            </div>

            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-slate-600">
                <p class="font-semibold text-blue-900 mb-1">Demo Credentials:</p>
                <p>Email: <code class="font-mono bg-white px-1 rounded">admin@pharmacy.local</code></p>
                <p>Password: <code class="font-mono bg-white px-1 rounded">Admin@123</code></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
