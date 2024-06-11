<?php $title= "Consultation iut";
require "view_begin.php"; 
?>

<div id="cont_case" class="container justify-content-center align-items-center">
	<h2 id="deco" class="badge rounded-pill" class="position-absolute top-0 start-0 translate-middle"> Données de l'IUT</h2>
		<div class="row">
			<!-- Première moitié de la page -->
			<div class="col-md-7 d-flex justify-content-center align-items-center">
			<!-- Votre contenu pour la première moitié de la page -->

			<div class='canv'>
        <canvas id="myChart"></canvas>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js"></script>

		<?php 
				$ls = [];
				$d = "";
				foreach ($data as $key => $value) {
					if(! in_array($value["label"],$ls)){
						$ls[]=$value["label"];
						$d .= '"'.$value["label"].'"'.",";

					}

				}
				$d = rtrim($d, ',');
				?>
    <script>
		Chart.defaults.color='#ffffff';
        var ctx = document.getElementById("myChart").getContext('2d');
		var couleur = ['rgba(173, 148, 126, 1)',    // Or secondaire
                                    'rgba(194, 138, 68, 1)',     // #C28A44
                                    'rgba(252, 187, 56, 1)',     // #FCBB38
                                    'rgba(252, 126, 56, 1)',       // #FC7E38
                                    'rgba(252, 235, 56, 1)',       // #FCEB38
                                    'rgba(173, 125, 87, 1)',       //#AD7D57
                                    'rgba(194, 94, 67, 1)',     //#C25E43
                                    'rgba(245, 172, 76, 1)',     //#F5AC4C
                                    'rgba(245, 142, 76, 1)',     //#F58E4C
                                    'rgba(245, 83, 76, 1)',     //#F5534C
                                    'rgba(245, 193, 76, 1)',    //#F5C14C
									'rgba(194, 138, 68, 1)',     // #C28A44
                                    'rgba(252, 187, 56, 1)',     // #FCBB38
                                    'rgba(252, 126, 56, 1)',       // #FC7E38
                                    'rgba(252, 235, 56, 1)',       // #FCEB38
									'rgba(194, 138, 68, 1)',     // #C28A44
                                    'rgba(252, 187, 56, 1)',     // #FCBB38
                                    'rgba(252, 126, 56, 1)',       // #FC7E38
                                    'rgba(252, 235, 56, 1)',       // #FCEB38
								];

        var myChart = new Chart(ctx, {
        	type: 'bar',
        	data: {
        		labels: [<?= $d ?>],
        		datasets: [
					<?php 
                $ls = [];
                foreach($data as $c => $v){
                    if(!in_array($v["sigle"],$ls)){
                        $ls[]=$v["sigle"];
                        echo "{ label: '".$v["sigle"]."' ,";
                        echo "backgroundColor: couleur[".$c."],";
                        $d = [];
                        $ls1 = [];
                        foreach($data as $nb){
                            if(!in_array($nb["label"], $ls1)) {
                                $ls1[]= $nb["label"];
                                $d[$nb["label"]] = 0;
                            }
                            if($nb["sigle"]==$v["sigle"]){
                                $d[$nb["label"]] = $nb["nb"];
                            }
                        }
                        $nd = implode(',' , $d);
                        echo "data: [".$nd."],},";
                    }
                }

                echo "],";
                ?>
        	},
        	options: {
        		tooltips: {
        			displayColors: true,
        			callbacks: {
        				mode: 'x',
        			},
        		},
        		scales: {
        			x: {
        				stacked: true,
        			},
        			y: {
        				stacked: true
        			}
        		},
        		responsive: true,
        	}
        });
	</script>



			</div>
			<!-- Deuxième moitié de la page -->
			<div class="col-md-5 justify-content-center align-items-center formulaire3">
				<!-- Votre contenu pour la deuxième moitié de la page -->
				<form method ="post" action="?controller=consultation&action=iut">

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="choix" value="annee"/>
							<label class="form-check-label c">Année</label>
						</div>
					</div>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="choix" value="semestre"/>
							<label class="form-check-label c">Semestre</label>
						</div>
					</div>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="choix" value="departement"/>
							<label class="form-check-label c">Département</label>
						</div>
					</div>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="choix" value="discipline"/>
							<label class="form-check-label c">Discipline</label>
						</div>
					</div>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="choix" value="formation"/>
							<label class="form-check-label c">Formation</label>
						</div>
					</div>

					<hr class='form_hr'/>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="filter" value="heures"/>
							<label class="form-check-label c">Heures</label>
						</div>
					</div>

					<div class="form-group2 b">
						<div class="form-check form-g">
							<input class="form-check-input" type="radio" name="filter" value="statut"/>
							<label class="form-check-label c">Statut</label>
						</div>
					</div>

					<button type="submit" value="Actualiser" class="form-group bouton_v2 ">Actualiser</button>

				</form>
			</div>
		</div>
</div>

<?php require "view_end.php"; ?>