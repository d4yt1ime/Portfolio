<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class="main-container">
    <div class="dashboard-container">
        <h1>Missions</h1>
        <?php require_once 'view_dashboard.php'; ?>
        <div class="add-mission-container">
            <button type="button" class="button-primary" onclick="window.location.href='<?= e($buttonLink) ?>'">
                + Créer Mission
            </button>
        </div>
    </div>
</div>

<?php
require 'view_end.php';
?>
