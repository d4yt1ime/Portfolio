<?php $title= "Declarer heures";
require "view_begin.php";
 ?>

<div class="container">
	<div class="row justify-content-center align-items-center">
		<div class="container col-lg-6 col-md-8 col-sm-10 col-12 formulaire">
			<p class="form-titre">Déclarer des heures</p>
				<form method="post" action="?controller=declaration&action=validation">
					<div class="form-group">
						<label>Heures
							<input type="text" class="form-control" size="30" name="heure" required/>
						</label>
					</div>

					<div class="form-group">
						<label>Année</label>
						<select name="annee" class="form-select" style="width: 350px;">
							<option selected>Choississez une option</option>
                                <?php foreach($annee as $c): ?>
									<?php foreach($c as $v): ?>
                                 		<option value="<?= e($v) ?>"> <?= e($v) ?> </option>
									<?php endforeach ?>
                                <?php endforeach ?>
						</select>
					</div>

                    <div class="form-group b">Semestre
						<?php foreach($semestre as $c): ?>
							<?php foreach($c as $v): ?>
								<div class="form-check form-g">
									<input class="form-check-input" type="radio" name="semestre" value="<?= e($v) ?>">
									<label class="form-check-label c"><?= e($v) ?></label>
								</div>
							<?php endforeach ?>
						<?php endforeach ?>
					</div>

					<div class="form-group b">Département
						<div class="form-check form-g d-block">
							<?php foreach($departement as $v): ?>
								<input class="form-check-input" type="radio" name="dept" value=<?= e($v["id"]) ?>>
								<label class="form-check-label c d-block"><?= e($v["nom"]) ?></label>
							<?php endforeach ?>
						</div>
					</div>

					<div class="form-group b">Type d'heure
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="type_h" value='statuaire'>
							<label class="form-check-label c">Statuaire</label>
						</div>
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="type_h" value='complementaire'>
							<label class="form-check-label c">Complémentaire</label>
						</div>
					</div>
						<button type="submit" value="Ajouter" class="form-group bouton_v2 ">Ajouter</button>
				</form>
		</div>
	</div>
</div>

<?php require "view_end.php"; ?>