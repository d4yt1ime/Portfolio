<!-- Vue permettant au prestataire de voir les missions qui lui ont été assignées -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class='main-container'>
    <div class="dashboard-container">
        <h1>Mes Missions</h1>
        <?php require_once 'view_dashboard.php'; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>
