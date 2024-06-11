<?php $title= "Ajouter un utilisateur";
require "view_begin.php";
?>

    <div id="cont_case" class="container">
        <h1 class="h1titre">Quel type d'utilisateur <br> voulez-vous ajouter ?</h1>
        <a href="?controller=annuaire&action=ajouter_form&poste=enseignant">
            <button class="ajout bouton_v2">Enseignant</button>
        </a>
        <a href="?controller=annuaire&action=ajouter_form&poste=secretaire">
            <button class="ajout bouton_v2">Secrétaire</button>
        </a>
    </div>

<?php require "view_end.php"; ?>