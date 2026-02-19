<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pharmacy Inventory System') ?></title>
    <?= vite_asset_tags() ?>
</head>
<body>
<div class="container">
    <?= $this->renderSection('content') ?>
</div>
</body>
</html>
