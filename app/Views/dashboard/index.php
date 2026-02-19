<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php include(APPPATH . 'Views/inventory/index.php'); ?>
<?= $this->endSection() ?>
