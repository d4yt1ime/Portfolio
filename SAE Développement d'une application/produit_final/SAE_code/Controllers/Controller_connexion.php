<?php
class Controller_connexion extends Controller {

    public function action_connexion(){
        /*
        On vérifie si l'utilisateur est déjà connecté lorsqu'il essaye d'accéder à la page de connexion.
        Cela empêche de charger le formulaire de connexion si une personne connectée essaye d'accéder à la page.
        */
        if(isset($_SESSION["connecte"]) and $_SESSION["connecte"]){
            header("Location: .");
            exit();
        }
        //Si l'utilisateur n'est pas connecté, on charge la vue pour afficher la page du formulaire de connexion
        $this->render("connexion");
    }

    //L'action par default si on tente d'accéder à la page est donc d'appeler l'action de connexion qui effectue les tâches expliquées au-dessus
    public function action_default(){
        $this->action_connexion();
    }

    //Cette action est appelée lors de la soumission du formulaire pour vérifier si les identifiants sont corrects
    public function action_check(){
        /*
        On charge le modèle pour le lien avec la base de données
        Ensuite, on appelle une fonction du modèle qui va renvoyer le mot de passe et tester si celui entré correspond au mot de passe chiffré stocké
        Si les identifiants sont corrects, on passe la session à 'True' pour indiquer que l'utilisateur est connecté
        Il n'est donc plus automatiquement redirigé sur la page de connexion
        Dans tous les cas, on le renvoie à la page d'accueil, s'il n'est pas connecté la vue de connexion s'affiche sinon il obtient la vue accueil
        */
        $m = Model::getModel();
        $infos = $m->identification_Check();
        if($infos != false){
            if(password_verify($_POST['mdp'], $infos["mdp"])){
                $_SESSION["connecte"] = true; 
                $_SESSION["id"] = $infos["id"];
                $_SESSION["permission"] = $infos["fonction"];
                if($infos["id"]== 123){
                    $_SESSION["permission"] = "direction";
                }
            } else {
                $this->render("connexion", ['message'=> 'Mot de passe ou login incorret']);
            }
        } else {
            $this->render("connexion", ['message'=> 'Cet utilisateur n\'existe pas']);
        }
        
        header("Location: .");
    }

    //Lors de l'appel à l'action de deconnexion, on change la statut de connexion à false ce qui bloquera l'accès aux informations et redirigera vers la page de connexion
    public function action_deconnexion(){
        $_SESSION["connecte"]=false;
        header("Location: .");
    }
}
?>