<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-8 max-w-7xl mx-auto px-2 md:px-6 lg:px-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-blue-900 flex items-center gap-2">📦 Inventory Management</h1>
            <p class="text-slate-600 text-sm">View and manage pharmaceutical inventory levels</p>
        </div>
        <a href="<?= site_url('inventory') ?>" class="btn btn-outline btn-small hidden md:inline-block">Reset Filters</a>
    </div>

    <!-- Filters Section -->
    <div class="card bg-white shadow rounded-lg p-4">
        <form method="get" action="<?= site_url('inventory') ?>">
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                <input type="text" id="search" name="search" value="<?= esc($search) ?>" placeholder="Enter SKU, brand, or generic name" class="form-input h-10 text-sm rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 w-full md:w-64" autocomplete="off">
                <select id="class" name="class" class="form-select h-10 text-sm rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500 w-full md:w-48">
                    <option value="">All Dosage Forms</option>
                    <?php foreach ($dosageForms as $form): ?>
                        <option value="<?= esc($form['dosage_form']) ?>" <?= $filterClass === $form['dosage_form'] ? 'selected' : '' ?>>
                            <?= esc($form['dosage_form']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary h-10 px-6 text-sm rounded-md shadow w-full md:w-auto flex items-center justify-center gap-1">🔍 <span>Search</span></button>
            </div>
        </form>
    </div>

    </div>

    <!-- Results Summary -->
    <div class="text-sm text-slate-600 flex justify-between items-center mt-2 mb-2">
        <span>Showing <?= !empty($products) ? (($currentPage - 1) * $perPage) + 1 : 0 ?> to <?= min($currentPage * $perPage, $totalProducts) ?> of <?= number_format($totalProducts) ?> products</span>
    </div>

    <!-- Inventory Table -->
    <?php if (!empty($products)): ?>
        <div class="card bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full text-sm">
                    <thead>
                        <tr class="bg-blue-700 text-white text-xs uppercase tracking-wider">
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Brand / Generic</th>
                            <th class="px-4 py-3">Dosage Form</th>
                            <th class="px-4 py-3 text-center">On Hand</th>
                            <th class="px-4 py-3 text-center">Reserved</th>
                            <th class="px-4 py-3 text-center font-bold bg-blue-800">Avail Qty</th>
                            <th class="px-4 py-3">Status & Reorder</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-blue-50 border-b transition">
                                <td class="px-4 py-3 font-mono text-xs font-bold text-blue-900"><?= esc($product['sku']) ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900 text-sm"><?= esc($product['brand_name']) ?></div>
                                    <div class="text-xs text-slate-500 italic"><?= esc($product['generic_name']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="bg-slate-100 px-2 py-1 rounded text-slate-700 font-medium shadow-sm"><?= esc($product['dosage_form']) ?></span>
                                    <div class="text-xs text-slate-400 mt-1"><?= esc($product['strength']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-blue-700 bg-blue-50 rounded"><?= esc($product['on_hand_qty'] ?? '0') ?></td>
                                <td class="px-4 py-3 text-center text-slate-600 bg-slate-50 rounded"><?= esc($product['reserved_qty'] ?? '0') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="space-y-2">
                                        <!-- Large colored badge for available qty -->
                                        <div class="inline-block px-4 py-2 rounded-lg font-bold text-lg shadow-sm <?= esc($product['status_color']) ?> border-2 border-current">
                                            <?= esc($product['available_qty']) ?>
                                        </div>
                                        <!-- Stock level bar -->
                                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mt-1">
                                            <?php 
                                                $maxLevel = max($product['reorder_level'] * 4, $product['on_hand_qty']);
                                                $percentage = min(100, ($product['on_hand_qty'] / $maxLevel) * 100);
                                                $barColor = $product['available_qty'] == 0 ? 'bg-red-500' : ($product['available_qty'] <= $product['reorder_level'] ? 'bg-orange-500' : ($product['available_qty'] <= $product['reorder_level'] * 2 ? 'bg-yellow-500' : 'bg-green-500'));
                                            ?>
                                            <div class="h-full <?= $barColor ?> transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-2">
                                        <!-- Status badge with icon -->
                                        <div class="inline-block">
                                            <?php 
                                                $icon = match($product['status']) {
                                                    'Out of Stock' => '⛔',
                                                    'Low Stock' => '⚠️',
                                                    'Adequate' => '⚡',
                                                    'Good Stock' => '✅',
                                                    default => '❓'
                                                };
                                            ?>
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold shadow-sm border <?= esc($product['status_color']) ?>">
                                                <?= $icon ?> <?= esc($product['status']) ?>
                                            </span>
                                        </div>
                                        <!-- Reorder level info -->
                                        <div class="text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">
                                            <strong>Reorder:</strong> <?= esc($product['reorder_level']) ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="card bg-white">
                <div class="flex items-center justify-center gap-2 flex-wrap">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= site_url('inventory?' . http_build_query(array_filter(['page' => $currentPage - 1, 'search' => $search, 'class' => $filterClass, 'sku' => $filterSkuPrefix]))) ?>" class="btn btn-outline btn-small">← Previous</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="btn btn-primary btn-small cursor-default"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= site_url('inventory?' . http_build_query(array_filter(['page' => $i, 'search' => $search, 'class' => $filterClass, 'sku' => $filterSkuPrefix]))) ?>" class="btn btn-outline btn-small"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= site_url('inventory?' . http_build_query(array_filter(['page' => $currentPage + 1, 'search' => $search, 'class' => $filterClass, 'sku' => $filterSkuPrefix]))) ?>" class="btn btn-outline btn-small">Next →</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card bg-slate-50 border-2 border-dashed border-slate-300 text-center py-12 rounded-lg shadow">
            <p class="text-slate-600 text-lg">📭 No products found</p>
            <p class="text-slate-500 text-sm mt-2">Try adjusting your search or filter criteria</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
