<?php

namespace app\Controller;

use app\App;
use app\Core\BaseController;
use app\Core\View;
use app\Model\AddressModel;
use app\Model\AdminModel;
use app\Model\UserModel;

class AuthController extends BaseController
{
    public function authPage(): void
    {
        $webDir = App::getConfig()->get('webDir');

        $this->styles[] = $webDir.'css/bootstrap/bootstrap.min.css';
        $this->styles[] = $webDir.'css/bootstrap/bootstrap-utilities.min.css';
        $this->styles[] = $webDir.'css/bootstrap/bootstrap-icons.min.css';

        $this->scripts[] = $webDir.'js/jquery-3.7.1.min.js';
        $this->scripts[] = $webDir.'js/bootstrap/bootstrap.min.js';

        $authView = new View('auth.php');
        $pageView = new View('page.php');

        try {
            $this->setViewVariable($pageView, 'content', $authView->getContent());
            $this->setViewVariable($pageView, 'styles', $this->styles);
            $this->setViewVariable($pageView, 'scripts', $this->scripts);

            $pageView->render();

        }catch (\Exception $e) {

        }
    }

    /**
     * Saves the user. If there is no id set in response, a new user will be created
     * @return void
     */
    public function signIn(){
        if(isset($_POST['username']) && isset($_POST['password'])){
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

            $admin = new AdminModel();
            if($admin->signIn($username, $password)){
                header('Location: /');
            }
        }else{
            header('Location: /auth');
        }
    }
}