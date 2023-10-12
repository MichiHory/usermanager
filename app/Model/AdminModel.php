<?php

namespace app\Model;

use app\App;
use app\Core\BaseModel;
use Exception;

class AdminModel extends BaseModel
{
    private int $id;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->database = App::getDatabase();
    }

    public static function isAdmin()
    {
        $isAdmin = $_SESSION['is_admin'] ?? false;
        $expirationTime = $_SESSION['auth_expiration_time'] ?? 0;

        return $isAdmin && $expirationTime > time();
    }

    public function signIn($username, $password)
    {
        $stmt = $this->database->prepare('SELECT * FROM admins WHERE username = :username');
        $stmt->bindParam('username', $username);
        $stmt->execute();

        $admin = $stmt->fetchObject(AdminModel::class);

        /** @var $admin AdminModel */
        if($admin){
            $verified = password_verify($password, $admin->password);
            if($verified){
                $_SESSION['is_admin'] = true;
                // Autorizace nastavena na hodinu
                $_SESSION['auth_expiration_time'] = time() + 60 * 60;
                return true;
            }
        }
        return false;
    }
}