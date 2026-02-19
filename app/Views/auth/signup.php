<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <div class="card">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Create Account</h1>
                <p class="text-slate-600 text-sm mt-1">Join the pharmacy inventory system</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error mb-4"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-error mb-4">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <div>• <?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('signup') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="<?= esc(old('full_name')) ?>" class="form-input" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" class="form-input" placeholder="john@example.com" required>
                </div>

                <div class="form-group">
                    <label for="role_name" class="form-label">Role</label>
                    <select id="role_name" name="role_name" class="form-select" required>
                        <option value="">Select a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= esc($role['name']) ?>" <?= old('role_name') === $role['name'] ? 'selected' : '' ?>>
                                <?= esc($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">🔐 Password (Required)</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    <div class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded text-sm">
                        <p class="font-semibold text-amber-900 mb-2">Password must contain:</p>
                        <ul class="space-y-1 text-amber-800">
                            <li>✓ Minimum 12 characters</li>
                            <li>✓ At least one UPPERCASE letter (A-Z)</li>
                            <li>✓ At least one lowercase letter (a-z)</li>
                            <li>✓ At least one number (0-9)</li>
                            <li>✓ At least one special character (@$!%*?&)</li>
                        </ul>
                        <p class="text-xs text-amber-700 mt-2">Example: <code class="bg-white px-1 rounded">MyPass123@Secure</code></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">Create Account</button>
            </form>

            <div class="mt-4 border-t border-slate-200 pt-4">
                <p class="text-center text-sm text-slate-600">
                    Already have an account? <a href="<?= site_url('login') ?>" class="text-blue-600 hover:text-blue-800 font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
