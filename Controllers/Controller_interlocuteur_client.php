<?php

class Controller_interlocuteur_client extends Controller
{

    /**
     * @inheritDoc
     */
    public function action_default()
    {
        // TODO: Implement action_default() method.
    }
        public function action_pagination(){
        $db=Model::getModel();
        if(isset($POST["page"])){
            $this->render("interlocuteur_client",$data=[
                "prestataires"=>$db->pagination($POST["page"])
            ]);

        }
    }
}
