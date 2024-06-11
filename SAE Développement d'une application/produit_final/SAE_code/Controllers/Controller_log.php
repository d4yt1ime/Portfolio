<?php 
class Controller_log extends Controller {

    public function action_log(){   
        $m=Model::getModel();
        $data['log']=$m->getLog();
        $this->render("log",$data);
    }

    public function action_default(){
        $this->action_log();
    }
}
?>