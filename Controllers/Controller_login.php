<?php

class Controller_login extends Controller
{

    /**
     * @inheritDoc
     */
    public function action_default()
    {
        // TODO: Implement action_default() method.
    }

    /**
     * @param $mail
     * @return bool
     */
    public function action_check_mail(){
        $mailExisting = false;

        if(isset($_POST['mail'])){
            $mail = $_POST['mail'];
            //à chiffrer
            $bd = Model::getModel();
            $mailExisting = $bd->mailExists($mail);
        }

        return $mailExisting;
    }

}