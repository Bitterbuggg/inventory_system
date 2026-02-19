<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div>
        <h1>📦 Inventory Management</h1>
        <p class="text-slate-600">View and manage pharmaceutical inventory levels</p>
    </div>

    <!-- Filters Section -->
    <div class="card bg-white">
        <h2 class="text-lg font-semibold mb-4">Search & Filter</h2>
        <form method="get" action="<?= site_url('inventory') ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-group">
                    <label for="search" class="form-label">Search (SKU / Name)</label>
                    <input type="text" id="search" name="search" value="<?= esc($search) ?>" placeholder="Enter SKU, brand, or generic name" class="form-input">
                </div>

                <div class="form-group">
                    <label for="sku" class="form-label">SKU Prefix</label>
                    <input type="text" id="sku" name="sku" value="<?= esc($filterSkuPrefix) ?>" placeholder="e.g., CLMP" class="form-input">
                </div>

                <div class="form-group">
                    <label for="class" class="form-label">Dosage Form</label>
                    <select id="class" name="class" class="form-select">
                        <option value="">All Dosage Forms</option>
                        <?php foreach ($dosageForms as $form): ?>
                            <option value="<?= esc($form['dosage_form']) ?>" <?= $filterClass === $form['dosage_form'] ? 'selected' : '' ?>>
                                <?= esc($form['dosage_form']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group flex items-end">
                    <button type="submit" class="btn btn-primary w-full">🔍 Search</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Legends -->
    <div class="card bg-gradient-to-r from-slate-50 to-white border-l-4 border-blue-500">
        <div class="space-y-4">
            <div>
                <h3 class="font-bold text-slate-900 mb-4 text-lg">📊 Stock Levels Legend (QTY FOR SALE):</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded border border-green-200">
                    <div class="w-6 h-6 bg-green-500 rounded-full"></div>
                    <div>
                        <div class="font-bold text-green-900">GOOD STOCK</div>
                        <div class="text-xs text-green-700">> 2x Reorder</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded border border-yellow-200">
                    <div class="w-6 h-6 bg-yellow-500 rounded-full"></div>
                    <div>
                        <div class="font-bold text-yellow-900">ADEQUATE</div>
                        <div class="text-xs text-yellow-700">1-2x Reorder</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-orange-50 rounded border border-orange-200">
                    <div class="w-6 h-6 bg-orange-500 rounded-full"></div>
                    <div>
                        <div class="font-bold text-orange-900">LOW STOCK</div>
                        <div class="text-xs text-orange-700">≤ Reorder</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded border border-red-200">
                    <div class="w-6 h-6 bg-red-500 rounded-full"></div>
                    <div>
                        <div class="font-bold text-red-900">OUT OF STOCK</div>
                        <div class="text-xs text-red-700">Zero QTY</div>
                    </div>
                </div>
            </div>
            <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-sm text-blue-900"><strong>Note:</strong> Colored quantity cells show AVAILABLE QTY FOR SALE (On Hand - Reserved)</p>
            </div>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="text-sm text-slate-600 flex justify-between items-center">
        <span>Showing <?= !empty($products) ? (($currentPage - 1) * $perPage) + 1 : 0 ?> to <?= min($currentPage * $perPage, $totalProducts) ?> of <?= number_format($totalProducts) ?> products</span>
    </div>

    <!-- Inventory Table -->
    <?php if (!empty($products)): ?>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Brand / Generic</th>
                            <th class="px-4 py-3">Dosage Form</th>
                            <th class="px-4 py-3 text-center">On Hand</th>
                            <th class="px-4 py-3 text-center">Reserved</th>
                            <th class="px-4 py-3 text-center font-bold bg-blue-700">AVAIL QTY FOR SALE</th>
                            <th class="px-4 py-3">Status & Reorder</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="hover:bg-slate-50 border-b">
                                <td class="px-4 py-3 font-mono text-sm font-bold"><?= esc($product['sku']) ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold"><?= esc($product['brand_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= esc($product['generic_name']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="bg-slate-100 px-2 py-1 rounded text-slate-700"><?= esc($product['dosage_form']) ?></span>
                                    <div class="text-xs text-slate-500 mt-1"><?= esc($product['strength']) ?></div>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-blue-600"><?= esc($product['on_hand_qty'] ?? '0') ?></td>
                                <td class="px-4 py-3 text-center text-slate-600"><?= esc($product['reserved_qty'] ?? '0') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="space-y-2">
                                        <!-- Large colored badge for available qty -->
                                        <div class="inline-block px-4 py-2 rounded-lg font-bold text-lg <?= esc($product['status_color']) ?> border-2 border-current">
                                            <?= esc($product['available_qty']) ?>
                                        </div>
                                        <!-- Stock level bar -->
                                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                            <?php 
                                                $maxLevel = max($product['reorder_level'] * 4, $product['on_hand_qty']);
                                                $percentage = min(100, ($product['on_hand_qty'] / $maxLevel) * 100);
                                                $barColor = $product['available_qty'] == 0 ? 'bg-red-500' : ($product['available_qty'] <= $product['reorder_level'] ? 'bg-orange-500' : ($product['available_qty'] <= $product['reorder_level'] * 2 ? 'bg-yellow-500' : 'bg-green-500'));
                                            ?>
                                            <div class="h-full <?= $barColor ?>" style="width: <?= $percentage ?>%"></div>
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
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold <?= esc($product['status_color']) ?>">
                                                <?= $icon ?> <?= esc($product['status']) ?>
                                            </span>
                                        </div>
                                        <!-- Reorder level info -->
                                        <div class="text-xs text-slate-600 bg-slate-50 px-2 py-1 rounded">
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
        <div class="card bg-slate-50 border-2 border-dashed border-slate-300 text-center py-12">
            <p class="text-slate-600 text-lg">📭 No products found</p>
            <p class="text-slate-500 text-sm mt-2">Try adjusting your search or filter criteria</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
