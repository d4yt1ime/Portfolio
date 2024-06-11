<?php 
class Controller_declaration extends Controller {

    public function action_declaration(){   
        $m = Model::getModel();
        $data = ["annee" => $m->getAnnee(), "semestre" => $m->getSemestre(),"departement" => $m->getDpt()];
        $this->render("declaration_form", $data);
    }

    public function action_default(){
        $this->action_declaration();
    }

    public function action_validation(){
        $m=Model::getModel();
        if(preg_match("/^[0-9]+$/",$_POST["heure"])){
            $m->ajouterHeure();
            $this->render("message", ["title" => ":)","message" => "Ajout réussi !"]);
        }
        else{
            $this->action_error("Informations non valide !");
        }
    }
}
?>
