<?php $title= "Liste des départements";
require "view_begin.php"; 
 ?>

<div id="cont_case" class="container justify-content-center align-items-center">
    <div > 
        <h2 id="deco" class="badge rounded-pill"> Liste des départements </h2>
    </div>
    <div  class="row">
        <?php foreach($libelledept as $n): ?> 
            <div class="card ldp" :hover style="max-height: 6rem;" >
                <a class="home-badge" aria-current="page" href="?controller=departement&action=departement&id=<?=e($n["id"]) ?>">
                    <h2 class='deco2'> <?= e($n["libelledept"])?></h2>      
                </a>
            </div>
        <?php endforeach?>
    </div>
</div>

<?php require "view_end.php"; ?>
