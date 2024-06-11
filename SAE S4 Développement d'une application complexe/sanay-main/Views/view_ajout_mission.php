<!-- Forumulaire permettant de créer une nouvelle mission -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class="add-container">
    <div class="form-abs">
        <h1>Ajout Mission</h1>
        <form action="?controller=gestionnaire&action=ajout_mission" method="post">
            <h2>Informations mission</h2>
            <input type="text" placeholder="Nom de la mission" name="mission" class="input-case" required>
            <select name="prestataire" id="prestataire">
                <option value="" disabled selected>Choisir un prestataire</option>
                <?php foreach ($prestataires as $prestataire): ?>
                <option value="<?= htmlspecialchars($prestataire['id']) ?>"><?= htmlspecialchars($prestataire['nom'] . ' ' . $prestataire['prenom']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="composante" id="composante">
                <option value="" disabled selected>Choisir une composante</option>
                <?php foreach ($composantes as $composante): ?>
                    <option value="<?= htmlspecialchars($composante['nom_composante']) ?>"><?= htmlspecialchars($composante['nom_composante']) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="form-names">
                <select name="type-bdl" required>
                    <option value="" disabled selected>Type de bon de livraison</option>
                    <option value="journee">Journée</option>
                    <option value="demi-journee">Demi-journée</option>
                    <option value="heure">Heure</option>
                </select>
                <input type="date" placeholder="Date de début" name="date-mission" class="input-case" required>
            </div>
            <div class="buttons" id="create">
                <button type="submit">Créer</button>
            </div>
        </form>
    </div>
</div>

<?php
require 'view_end.php';
?>
