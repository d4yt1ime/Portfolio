<?php $title= "Annuaire";
require "view_begin.php"; 
?>

<form>
	<p>
        <input type="hidden" name="controller" value="annuaire"/>
    </p>
	<p>
        <input type="hidden" name="action" value="annuaire"/>
    </p>
	<p class="ajt">
        <label>
            <input class= 'resr' type="text" name="recherche" placeholder='Rechercher...'/>  
            <input class='bouton_v3' type="submit" value="Rechercher"/>
        </label>
    </p>	
</form>

<?php if(isset($_SESSION["permission"]) and $_SESSION["permission"]=="direction"):?>
    <p class='ajt'>
        <label>
            <a href="?controller=annuaire&action=ajouter">
                <button class='bouton_v3'>Ajouter</button>
            </a>
        </label>
    </p>
<?php endif ?>

<div id='cont_case' class='container-fluid col justify-content-center align-items-center'>
    <div class='row marge'>
        <table>
            <tr class='texttr' class='row'>
                <td>
                    <strong>Nom</strong>
                </td>
                <td>
                    <strong>Prénom</strong>
                </td>
                <td>
                    <strong>Grade</strong>
                </td>
            </tr>
        <?php foreach($infos as $v): ?>
            <tr>
                <td>
                    <a class="lien" href="?controller=profil&action=profil&id=<?= e($v["id_personne"])?>"><?=e($v["nom"])?></a>
                </td>
                <td>
                    <a class="lien" href="?controller=profil&action=profil&id=<?= e($v["id_personne"])?>"><?=e($v["prenom"])?></a>
                </td>
                <td class="fonction">
                    <?= e($v["fonction"])?>
                </td>
        <?php if(isset($_SESSION["permission"]) and $_SESSION["permission"]=="direction"):?>     
                <td>
                    <a href="?controller=annuaire&action=supprimer&id=<?= e($v["id_personne"])?>"><img class="croix" src="Content/img/icons8-cross-in-circle-100.png" alt="supprimer"/> </a>
                </td>
        <?php endif ?>
            </tr>
        <?php endforeach ?>
        </table>
    </div>
</div>

<?php require "view_end.php"; ?>