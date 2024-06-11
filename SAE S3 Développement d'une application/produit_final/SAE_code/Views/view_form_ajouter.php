<?php $title= "Ajouter un utilisateur";
require "view_begin.php"; 
 ?>

<div class="container">
	<div class="row justify-content-center align-items-center">
		<div class="container col-lg-6 col-md-8 col-sm-10 col-12 formulaire">
			<p class="form-titre">Ajouter un utilisateur</p>
				<form method="post" action="?controller=annuaire&action=validation">
                    <input type="hidden" value="<?php if(isset($_GET["poste"])){echo e($_GET["poste"]);} ?>" name="poste"/>
						<div class="form-group"><label>ID Interne<input type="text" class="form-control" size="30" name="id" required/></label></div>
						<div class="form-group"><label>MDP<input type="text" class="form-control" size="30" name="mdp"/></label></div>
						<div class="form-group"><label>Nom<input type="text" class="form-control" size="30" name="nom" required/></label></div>
						<div class="form-group"><label>Prénom<input type="text" class="form-control" size="30" name="prenom" required/></label></div>
						<div class="form-group"><label>Email<input type="text" class="form-control" size="30" name="email"/></label></div>
						<div class="form-group"><label>Téléphone<input type="text" class="form-control" size="30" name="phone"/></label></div>

                        <?php if(isset($_GET["poste"]) and $_GET["poste"] == "enseignant"):?>

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

						<div class="form-group">
							<label>Statut</label>
							<select name="statut" class="form-select" style="width: 350px;;">
								<option selected>Choississez une option</option>
                                <?php foreach($list["statut"] as $v): ?>
                                    <option value="<?= e($v["siglecat"]) ?>"> <?= e($v["siglecat"]) ?> </option>
                                <?php endforeach ?>
						  	</select>
						</div>

						<div class="form-group">
							<label>Discipline</label>
							<select name="discipline" class="form-select" style="width: 350px;;">
								<option selected>Choississez une option</option>
                            		<?php foreach($list['discipline'] as $v): ?>
                                		<option value="<?= e($v["libelledisc"]) ?>"> <?= e($v["libelledisc"]) ?> </option>
                            		<?php endforeach ?>
						  	</select>
						</div>

						<div class="form-group b">Départements</br>
                        	<?php foreach($list['departements'] as $v): ?>
								<div class="form-check form-g">
									<input class="form-check-input" type="checkbox" name="departements[]" value="<?= e($v["libelledept"]) ?>"/>
									<label class="form-check-label c"><?= e($v["libelledept"]) ?></label>
								</div>
                        	<?php endforeach ?>

						<div class="form-group b">Membre de direction
							<div class="form-check form-g">
								<input class="form-check-input" type="radio" name="direction" value="true">
								<label class="form-check-label">Oui</label>
							</div>
							<div class="form-check form-g">
								<input class="form-check-input" type="radio" name="direction" value="true">
								<label class="form-check-label">Non</label>
							</div>
						</div>

                        <?php endif ?>
						<button type="submit" value="Ajouter" class="form-group bouton_v2 ">Ajouter</button>
				</form>
		</div>
	</div>
</div>

<?php require "view_end.php"; ?>