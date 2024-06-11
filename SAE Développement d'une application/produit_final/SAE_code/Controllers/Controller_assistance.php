<?php 

class Controller_assistance extends Controller {

    public function action_assitance(){   
        $m = Model::getModel();
        $this->render("assistance", ["profil" => $m->getInfoProfil($_SESSION['id']),]);
    }

    public function action_default(){
        $this->action_assitance();
    }
}

?>