<?php $title = "Message";
require "view_begin.php"; ?>

<div id="cont_case" class="container">
    <h1 class="gold">
        <?= e($title) ?>
    </h1>
    <p class="white2" style="margin-top:30px">
        <?= e($message) ?>
    </p>
</div>

<?php require "view_end.php"; ?>