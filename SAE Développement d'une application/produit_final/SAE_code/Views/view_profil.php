<?php $title= "Profil";
require "view_begin.php";
 ?>

<!--container-fluid permet d'obtenir un affichage responsive et qui prend toute la page-->
<div id="cont_case" class="container justify-content-center align-items-center">
	<div class="row">
		<!-- Première moitié de la page -->
		<div class="col-md-5 d-flex justify-content-center align-items-center">
			<!-- Votre contenu pour la première moitié de la page -->
			<img class="photo_profil" src="Content/img/profil/<?=e($id)?>.jpg"/>
		</div>
	  		<!-- Deuxième moitié de la page -->
		  	<div class="col-md-7 justify-content-center align-items-center">
				<!-- Votre contenu pour la deuxième moitié de la page -->
				<div class="row text">
					<span>
						<div class="prenom_nom">
							<?= e($prenom)." ".e(strtoupper($nom))?>
						</div>
						<div>
							<span class="gold">
								<?=e(strtoupper($fonction))?>
							</span> 
							<span>
								<?="id: ".e($id)?>
							</span>
						</div>
					</span>

					<span class="hr"><hr class='trait'></span>

					<span>
						<div>
							<img class='icone_profil' src="Content/img/icons8-mail-100.png"/><?="   ".e($email)?>
						</div>
						<div>
							<img class='icone_profil' src="Content/img/icons8-phone-100.png"/><?="   ".e($phone)?>
						</div>
					</span>

                    <?php if($fonction != "secretaire" and $fonction != "personne"):?>

					<span class="hr"><hr class='trait'></span>

					<span>
						<div>
							<span class="gold"> Département : </span>
							<?php foreach($depts as $c => $d): ?> 
								<?php echo e($d["depts"]); 
								if($c != count($depts)-1){echo " /";}?>
								<?php endforeach ?>
						</div>
						<div>
							<span class="gold">Discipline : </span><?=e($discipline)?>
						</div>
						<div>
							<span class="gold">Catégorie d'agent : </span><?=e($statut)?>
						</div>
					</span>
                    <?php endif;
					
                    if($id == $_SESSION["id"] or $_SESSION["permission"] == "direction"): ?>
					<label>
						<a href="?controller=profil&action=modification
						<?php if(isset($_GET["id"]) and $_SESSION["permission"] == "direction"){
							echo "&id=".$_GET["id"];
						}?>">
						<button class="bouton">Modifier</button></a>
					</label>
                    <?php endif ?>
				</div>
		  	</div>
	</div>
</div>

<?php require "view_end.php"; ?>