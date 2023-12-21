<?php

class Controller_login extends Controller
{

    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_login_form();
    }

    public function action_login_form(){
        $this->render("login");
    }

    public function action_check_pswd(){
        $m=Model::getModel();
        if(isset($_POST['mail']) && isset($_POST['password'])){
            return $m->checkMailPassword($_POST['mail'],$_POST['password'];
        }
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

