<?php

namespace app\Model;

use app\App;
use app\Core\BaseModel;
use Exception;

class UserModel extends BaseModel
{
    private ?int $id = null;
    private string $name;
    private string $surname;
    private ?string $email = null;
    private ?string $phone = null;
    private ?AddressModel $address = null;

    private string $password;

    public function __construct()
    {
        $this->database = App::getDatabase();
    }

    /**
     * Used to create a new user. To load an existing user, use the loadUserById method
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $surname
     * @param string|null $phone
     * @param AddressModel|null $address
     * @return void
     * @throws Exception
     */
    public function createUser(
        string $email,
        string $password,
        string $name,
        string $surname,
        ?string $phone = null,
        ?AddressModel $address = null
    ): void
    {
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->setPassword($password);
        $this->address = $address;
    }

    /**
     * @param $string
     * @return void
     * @throws Exception
     */
    public function setPassword($string): void
    {
        $string = preg_replace('/\s+/', '', $string);

        if(!empty($string)){
            $string = htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
            $pass_hash = password_hash($string, PASSWORD_BCRYPT);

            if($pass_hash){
                $this->password = $pass_hash;
            }
        }else{
            throw new Exception('Password is empty');
        }

    }

    /**
     * Saves the object to the database, if it already has an id assigned (the object exists), it updates it.
     * @return bool
     */
    public function save(): bool
    {
        if(isset($this->id)){
            $stmt = $this->database->prepare('
                UPDATE users 
                SET name = :name, surname = :surname, email = :email, phone = :phone, password = :password
                WHERE id = :id'
            );
            $stmt->bindParam('id', $this->id);
        }else{
            $stmt = $this->database->prepare('
                INSERT INTO users (name, surname, email, phone, password) 
                VALUES (:name, :surname, :email, :phone, :password)'
            );
        }

        $stmt->bindParam('name', $this->name);
        $stmt->bindParam('surname', $this->surname);
        $stmt->bindParam('email', $this->email);
        $stmt->bindParam('phone', $this->phone);
        $stmt->bindParam('password', $this->password);

        $result = $stmt->execute();

        if(!isset($this->id) && $result){
            $this->id = $this->database->lastInsertId();
        }

        if($result && isset($this->address)){
            $result = $this->address->save();
        }

        return $result;
    }

    /**
     * Set address property
     * @param AddressModel $address
     * @return void
     */
    public function setAddress(AddressModel $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): ?AddressModel
    {
        return $this->address;
    }

    /**
     * Loads user from database by id
     * @param int $id
     * @param \PDO $database
     * @return false|UserModel
     */
    public static function loadUserById(int $id, \PDO $database): false|UserModel{
        $stmt = $database->prepare('SELECT * FROM users WHERE id=:id');
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $user = $stmt->fetchObject(UserModel::class);
        if($user){
            $address = AddressModel::loadAddressByUserId($user->getId(), $database);

            if ($address){
                /** @var $user UserModel */
                $user->setAddress($address);
            }
        }

        return $user;
    }

    /**
     * @param int $id
     * @param \PDO $database
     * @return bool
     */
    public static function deleteUserById(int $id, \PDO $database): bool
    {
        $stmt = $database->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam('id', $id);


        return $stmt->execute();
    }

    /**
     * Returns All users from database
     * @param \PDO $database
     * @return array
     */
    public static function getAllUsers(\PDO $database): array
    {
        $results =  $database->query('SELECT * FROM users')->fetchAll(\PDO::FETCH_CLASS, UserModel::class);

        if($results){
            /** @type $result UserModel */
            foreach ($results as $result){
                $address = AddressModel::loadAddressByUserId($result->getId(), $database);

                if($address){
                    $result->setAddress($address);
                }
            }
        }

        return $results ? $results : [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name property and validate it
     * @throws Exception
     */
    public function setName(string $name): void
    {
        $name = trim($name);
        if (preg_match('/^\D*$/u', $name)) {
            $this->name = $name;
        }else{
            throw new Exception('Name contains forbidden symbols');
        }
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * Set Surname property and validate it
     * @throws Exception
     */
    public function setSurname(string $surname): void
    {
        $surname = trim($surname);
        if (preg_match('/^\D*$/u', $surname)) {
            $this->surname = $surname;
        }else{
            throw new Exception('Surname contains forbidden symbols');
        }
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set Email property and validate it
     * @throws Exception
     */
    public function setEmail(?string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('Email address is not valid');
        }
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set Phone property and validate it
     * @throws Exception
     */
    public function setPhone(?string $phone): void
    {
        if (!isset($phone)) return;
        $sanitizedPhone = preg_replace('/[ \-()]/', '', $phone);
        if(preg_match('/^\+?\d+$/', $sanitizedPhone)){
            $this->phone = $sanitizedPhone;
        }else{
            throw new Exception('Phone number is not valid');
        }
    }
}