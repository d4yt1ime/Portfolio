<?php $title= "Département";
require "view_begin.php"; ?>

<div id='cont_case' class='container-fluid justify-content-center align-items-center'>
    <h1 class="h1titre">Département 
        <p class='gold'>
            <?=e(mb_strtoupper($info['libelledept']))?>
        </p>
    </h1>
    <table class='tb'>
        <tr>
            <th class="gold2 basic" scope='row'>Nom</th>
            <td class="white2 basic t">
                <?= e($info['libelledept'])?>
            </td>
        </tr>
        <tr>
            <th class="gold2 basic" scope='row'>Parcours</th>
            <td class="white2 basic t">
                <?php foreach($nomf as $n): ?>
                    <?=$n["nom"]?>
                <?php endforeach?>
            </td>
        </tr>
        <tr>
            <th class="gold2 basic" scope='row'>Effectif</th>
            <td class="white2 basic t">
                <?=e($effectif)?>
            </td>
        </tr>
        <tr>
            <th class="gold2 basic" scope='row'>Besoin en nombre heure</th>
            <td class="white2 basic t">
                <?=e($besoinh["sum"])?>
            </td>
        </tr>
    </table>
<?php if($_SESSION["permission"]=="chefdedpt"): ?>
    <p class='ajt'>
        <a href="?controller=departement&action=demande">
            <button class='bouton_v2'>Demandes</button>
        </a>
    </p>
<?php endif?>
</div>

<?php require "view_end.php"; ?>