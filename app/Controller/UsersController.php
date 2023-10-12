<?php

namespace app\Controller;

use app\App;
use app\Core\BaseController;
use app\Core\View;
use app\Model\AddressModel;
use app\Model\UserModel;
use Exception;
use stdClass;
use Throwable;

class UsersController extends BaseController
{
    public function showUsers(): void
    {
        $webDir = App::getConfig()->get('webDir');

        $this->styles[] = $webDir.'css/global.css';
        $this->styles[] = $webDir.'css/bootstrap/bootstrap.min.css';
        $this->styles[] = $webDir.'css/bootstrap/bootstrap-utilities.min.css';
        $this->styles[] = $webDir.'css/bootstrap/bootstrap-icons.min.css';
        $this->styles[] = $webDir.'css/style.css';

        $this->scripts[] = $webDir.'js/jquery-3.7.1.min.js';
        $this->scripts[] = $webDir.'js/bootstrap/bootstrap.min.js';
        $this->scripts[] = $webDir.'js/User.js';
        $this->scripts[] = $webDir.'js/Address.js';
        $this->scripts[] = $webDir.'js/app.js';

        $userView = new View('users.php');
        $pageView = new View('page.php');

        try {

            $users = UserModel::getAllUsers(App::getDatabase());

            $this->setViewVariable($userView, 'users', $users);
            $this->setViewVariable($pageView, 'content', $userView->getContent());
            $this->setViewVariable($pageView, 'styles', $this->styles);
            $this->setViewVariable($pageView, 'scripts', $this->scripts);

            $pageView->render();

        }catch (Exception $e) {
        }
    }

    /**
     * Saves the user. If there is no id set in response, a new user will be created
     * @return void
     */
    public function saveUser(): void
    {
        $response = new stdClass();
        $response->success = false;

        if (isset($_POST['user'])){
            $data = $_POST['user'];

            try{
                // If the id is set, and it is a number, I will edit the user
                if(!empty($data['id']) && ctype_digit($data['id'])){
                    $user = UserModel::loadUserById((int)$data['id'], App::getDatabase());
                    if($user){
                        $user->setName($data['name']);
                        $user->setSurname($data['surname']);
                        $user->setEmail($data['email']);

                        // Sets the password only when it is changed
                        if(!empty($data['password'])){
                            $user->setPassword($data['password']);
                        }

                        $phone = empty($data['phone']) ? null : $data['phone'];
                        $user->setPhone($phone);

                        // If the user has an address, I will update it, otherwise I will create a new one if it came in the request
                        if($user->getAddress() !== null){
                            $address = $user->getAddress();
                            $address->setStreet($data['address']['street']);
                            $address->setCity($data['address']['city']);
                            if(!empty($data['address']['zip'])){
                                $address->setZip($data['address']['zip']);
                            }

                            $user->setAddress($address);
                        }elseif (
                            !empty($data['address']['street']) &&
                            !empty($data['address']['city']) &&
                            !empty($data['address']['zip'])
                        ){
                            $address = new AddressModel();
                            $address->createAddress(
                                $user->getId(),
                                $data['address']['street'],
                                $data['address']['city'],
                                $data['address']['zip']
                            );
                            $user->setAddress($address);
                        }

                        $user->save();
                    }
                // If I don't have a user id, I will create a new one
                }else{
                    $user = new UserModel();
                    $user->createUser(
                        $data['email'],
                        $data['password'],
                        $data['name'],
                        $data['surname'],
                        empty($data['phone']) ? null : $data['phone']
                    );

                    $user->save();

                    // If the request contains an address, I create it for the user
                    if(
                        !empty($data['address']['street']) &&
                        !empty($data['address']['city']) &&
                        !empty($data['address']['zip'])
                    ){
                        $address = new AddressModel();
                        $address->createAddress(
                            $user->getId(),
                            $data['address']['street'],
                            $data['address']['city'],
                            $data['address']['zip']
                        );
                        $address->save();
                    }
                }

                $response->success = true;
                $response->data = ['userId' => $user->getId()];
            }catch(Throwable $throwable){
                $response->success = false;
                $response->errorMsg = $throwable->getMessage();
            }
        }
        echo (json_encode($response));
    }

    /**
     * Processing a request to delete a user
     * @return void
     */
    public function deleteUser(): void
    {
        $response = new stdClass();

        if (isset($_POST['userId'])){
            $userId = $_POST['userId'];

            try {
                UserModel::deleteUserById($userId, App::getDatabase());
                $response->success = true;
            }catch (Throwable $throwable){
                $response->success = false;
                $response->errorMsg = $throwable->getMessage();
            }
        }
        echo (json_encode($response));
    }
}