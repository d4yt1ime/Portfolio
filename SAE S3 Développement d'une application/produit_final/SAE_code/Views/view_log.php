<?php $title= "Log";
require "view_begin.php";?>

<div id='cont_case' class='container-fluid justify-content-center align-items-center'>
    <h1 class="h1titre">Log</h1>
        <table class='tb'>
            <tr>
                <th class='gold2 basic'>Action</th>
                <th class='gold2 basic'>Date de modification</th>
                <th class='gold2 basic'>Modification</th>
            </tr>
            <?php foreach($log as $l):?>
                <tr>
                    <td class="white2 basic">
                        <?= e($l['action'])?>
                    </td>
                    <td class="white2 basic">
                        <?= e($l['date_modif'])?>
                    </td>
                    <td class="white2 basic">
                        <?php if (e($l['action']) == 'INSERT') { ?>
                            <?= e($l['nom']) . " " . e($l['prenom']) ." a été ajouté dans la bdd"?>
                        <?php } else if (e($l['action']) == 'UPDATE') { ?>
                            <?= e($l['nom']) . " " . e($l['prenom']) ." a été modifié dans la bdd"?>
                        <?php } else { ?>
                            <?= e($l['nom']) . " " . e($l['prenom']) ." a été supprimé de la bdd"?>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach?>
        </table>
</div>

<?php require "view_end.php"; ?>