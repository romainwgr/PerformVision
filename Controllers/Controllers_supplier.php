<?php 
class Controllers_supplier extends Controller{
    public function action_default(){
        $this->action_dashboard();

    }
    public function action_dashboard(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $m = Model::getModel();
        $data = [
            "texte"=>$m->gettext("supplier"),
        ];
    }
 }
?>