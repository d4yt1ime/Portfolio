<?php
class Controller_consultation extends Controller {

    public function action_consultation(){
        $m=Model::getModel();
        $data["data"] = $m->getHeure($_SESSION["id"]);
        $data["profil"]=$m->getInfoProfil($_SESSION["id"]);
        $this->render("consultation_heure", $data);
    }

    public function action_iut(){
        $m=Model::getModel();
        $data["data"] = $m->getIUT();
        $this->render("consultation_iut", $data);
    }

    public function action_default(){
        $this->action_consultation();
    }
}
?>