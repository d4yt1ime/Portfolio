<?php $title= "Consultation heure";
require "view_begin.php"; 
?>

<div id="cont_case" class="container justify-content-center align-items-center">
    <h2 id="deco" class="badge rounded-pill" class="position-absolute top-0 start-0 translate-middle"> <?="Heures de ".e(strtoupper($profil["nom"]))." ".e($profil["prenom"])?>  </h2>
        <div class="row">
		<!-- Première moitié de la page -->
		    <div class="col-md-7 d-flex justify-content-center align-items-center">
			    <!-- Votre contenu pour la première moitié de la page -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                    let ctx = document.getElementById("myChart").getContext("2d");
                    let data = {
                        labels: [<?php foreach($data as $c => $v): ?><?php echo "'".e($v["label"])."'"; if($c != count($data)-1){echo " ,";}?><?php endforeach ?>],
                        datasets: 
                        [
                            {
                                data: [<?php foreach($data as $c => $v): ?><?php  echo e($v["heures"]); if($c != count($data)-1){echo ",";}?><?php endforeach ?>],
                                backgroundColor: [
                                    'rgba(173, 148, 126, 1)',    // Or secondaire
                                    'rgba(194, 138, 68, 1)',     // #C28A44
                                    'rgba(252, 187, 56, 1)',     // #FCBB38
                                    'rgba(252, 126, 56, 1)',       // #FC7E38
                                    'rgba(252, 235, 56, 1)',       // #FCEB38
                                    'rgba(173, 125, 87, 1)',       //#AD7D57
                                    'rgba(194, 94, 67, 1)',     //#C25E43
                                    'rgba(245, 172, 76, 1)',     //#F5AC4C
                                    'rgba(245, 142, 76, 1)',     //#F58E4C
                                    'rgba(245, 83, 76, 1)',     //#F5534C
                                    'rgba(245, 193, 76, 1)',     //#F5C14C
                                ],
                            }
                        ]
                    };

                    let myChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: {
                        responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                        labels: {
                                            color: 'rgb(255, 255, 255)',
                                        }
                                }
                            }
                        }
                    });
                    console.log(data);
                    });
                </script>
			    <canvas id="myChart"></canvas>
		    </div>
  
		<!-- Deuxième moitié de la page -->
		    <div class="col-md-5 justify-content-center align-items-center formulaire2">
			    <!-- Votre contenu pour la deuxième moitié de la page -->
			    <form method ="post" action="#">

				<div class="form-group2 b">
					<div class="form-check form-g">
						<input class="form-check-input" type="radio" name="filter" value="annee"/>
						<label class="form-check-label c">Année</label>
					</div>
				</div>

				<div class="form-group2 b">
					<div class="form-check form-g">
						<input class="form-check-input" type="radio" name="filter" value="semestre"/>
						<label class="form-check-label c">Semestre</label>
					</div>
				</div>

				<div class="form-group2 b">
					<div class="form-check form-g">
						<input class="form-check-input" type="radio" name="filter" value="departement"/>
						<label class="form-check-label c">Département</label>
					</div>
				</div>

				<div class="form-group2 b">
					<div class="form-check form-g">
						<input class="form-check-input" type="radio" name="filter" value="types"/>
						<label class="form-check-label c">Type</label>
					</div>
				</div>

                <button type="submit" value="Ajouter" class="form-group bouton_v2 ">Actualiser</button>

			    </form>
		    </div>
	    </div>
</div>

<?php require "view_end.php"; ?>