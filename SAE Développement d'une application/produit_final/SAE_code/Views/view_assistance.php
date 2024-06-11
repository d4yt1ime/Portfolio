<?php $title= "Assistance";
require "view_begin.php";
?>

<div class="container">
	<div class="row justify-content-center align-items-center">
		<div class="container col-lg-6 col-md-8 col-sm-10 col-12 formulaire">
			<p class="form-titre">Assistance</p>
			<form action="#" method="post">

				<div class="form-group">
					<label>Nom<input type="text" class="form-control" size="25" value="<?= $profil['nom']?>" readonly></label>
					<label>Prénom<input type="text" class="form-control" size="25" value="<?= $profil['prenom']?>" readonly></label>
				</div>
					
				<div class="form-group">
					<label>Identifiant<input type="text" class="form-control" size="30" value="<?= $profil['id']?>" readonly></label>
				</div>

				<div class="form-group">
					<label>Mail<input type="email" class="form-control" size="30" value="<?= $profil['email']?>" readonly></label>
				</div>
		
				<div class="form-group">
					<label>Objet<input type="text" class="form-control" size="30" required></label>
				</div>
		
				<div class="form-group">
					<label>Demande<textarea class="form-control" rows="5" cols="40" required></textarea></label>
				</div>
		
				<button type="submit" class="form-group bouton_v2 ">Envoyer</button>
		
			</form>
		
		</div>
	</div>
</div>

<?php require "view_end.php" ?>