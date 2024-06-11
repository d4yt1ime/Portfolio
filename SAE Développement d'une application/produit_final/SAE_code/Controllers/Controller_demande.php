<?php 
class Controller_demande extends Controller {

    public function action_demande(){   
        $m=Model::getModel();
        $data['demande']=$m->getDemande();
        $this->render("demande", $data);
    }

    public function action_default(){
        $this->action_demande();
    }
}
?>