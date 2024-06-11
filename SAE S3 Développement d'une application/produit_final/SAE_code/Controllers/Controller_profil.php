<?php 
class Controller_profil extends Controller {

    public function action_profil(){
        $m = Model::getModel();
        if(isset($_GET["id"]) and $m->id_in_db($_GET["id"])){
            $data = $m->getInfoProfil($_GET["id"]);
        }
        else {
            $data = $m->getInfoProfil($_SESSION["id"]);
        }
        $this->render("profil", $data);
    }

    public function action_default(){
        $this->action_profil();
     } 

    public function action_modification(){
        $m = Model::getModel();
        $id = $_SESSION["id"];
        if(isset($_GET["id"]) and $_SESSION["permission"] == "direction"){
            $id = $_GET["id"];
        }
        $data = ['profil'=> $m->getInfoProfil($id),'list'=> $m->getCatDiscDpt(),'annee' => $m->getAnnee(), "semestre" => $m->getSemestre()];
        $this->render("modification", $data);
    }

    public function action_modifier(){
        if(isset($_POST["id"])){
            $infos["id"] = $_POST["id"];
            $infos["nom"] = $_POST["nom"];
            $infos["prenom"] = $_POST["prenom"];
            $infos["email"] = $_POST["email"];
            $infos["phone"] = $_POST["phone"];
            if(preg_match("/^ *$/",$_POST["mdp"])){
                $infos['mdp'] = null;
            } else {
                $infos["mdp"] = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
            }
            if($_POST["fonction"] != "secretaire" and $_POST["fonction"] != "personne"){
                $infos['annee'] = $_POST["annee"];
                $infos['semestre'] = $_POST["semestre"];
                $infos["statut"] = $_POST["statut"];
                $infos["discipline"] = $_POST["discipline"];
                $infos["departements"] = $_POST["departements"];
            }
            $infos["fonction"] = $_POST["fonction"];
            $m = Model::getModel();
            $m->updateProfil($infos);
            $this->action_default();
        } else {
            $this->render("message", ["title" => ":)","message" => "Modif pas réussi !"]);
        }
        
    }
}
?>