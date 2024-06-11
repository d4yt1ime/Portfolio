<?php 
class Controller_accueil extends Controller {

    public function action_accueil(){   
        $m=Model::getModel();
        $data=[];
        if(isset($_SESSION["id"])){
            $data=$m->getInfoProfil($_SESSION["id"]);
        }
        $this->render("accueil", $data);
    }

    public function action_default(){
        $this->action_accueil();
    }
}
?>