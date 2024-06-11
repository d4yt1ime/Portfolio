<?php 
class Controller_annuaire extends Controller {

    public function action_annuaire(){
        $m = Model::getModel();
        $data = ["infos" => $m->getList()];
        $this->render("annuaire",$data);
    }

    public function action_default(){
        $this->action_annuaire();
    }

    public function action_ajouter(){
        $this->render("ajouter");
    }

    public function action_ajouter_form(){
        $m = Model::getModel();
        $data = ["list" => $m->getCatDiscDpt(), "annee" => $m->getAnnee(), "semestre" => $m->getSemestre()];
        $this->render("form_ajouter", $data);
    }

    public function action_validation(){
        $m=Model::getModel();
        if(preg_match("/^[0-9]+$/",$_POST["id"]) and ! $m->id_in_db($_POST["id"])){
            $infos["poste"]=$_POST["poste"];
            $infos["id"]=$_POST["id"];
            $infos["fonction"]=$_POST["poste"];
            $infos["nom"]=(!preg_match("/^ *$/",$_POST["nom"]))?$_POST["nom"]:"???";
            $infos["prenom"]=(!preg_match("/^ *$/",$_POST["prenom"]))?$_POST["prenom"]:"???";
            $infos["email"]=(!preg_match("/^ *$/",$_POST["email"]))?$_POST["email"]:"???";
            $infos["phone"]=(!preg_match("/^ *$/",$_POST["phone"]))?$_POST["phone"]:"???";
            $infos["mdp"]=(!preg_match("/^ *$/",$_POST["mdp"]))?password_hash($_POST["mdp"], PASSWORD_DEFAULT):"0";
            if($_POST["poste"]=="enseignant"){
                $infos["annee"]=$_POST["annee"];
                $infos["semestre"]=$_POST["semestre"];
                $infos["statut"]=$_POST["statut"];
                $infos["discipline"]=$_POST["discipline"];
                $infos["direction"]=$_POST["direction"];
                if(isset($_POST["departements"])){
                    $infos["departements"]=$_POST["departements"];
                }
            }
            $m->ajouterUtilisateur($infos);
            $this->render("message", ["title" => ":)","message" => "Ajout réussi !"]);
        }
        else{
            $this->action_error("Informations non valide !");
        }
    }

    public function action_supprimer(){
        if(isset($_SESSION["permission"]) and $_SESSION["permission"]=="direction"){
            $m = Model::getModel();
            $m->supprimerUtilisateur($_GET["id"]);
            $this->action_default();
        }
    }
}
?>