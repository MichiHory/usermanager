<?php

namespace app\Model;

use app\App;
use Exception;

class AddressModel
{
    private int $id;

    private int $user_id;

    private string $street;

    private string $city;

    private int $zip;

    public function __construct()
    {
        $this->database = App::getDatabase();
    }

    /**
     * Saves the address to the database. If any of the required object properties are not set, the address is not saved or is deleted
     * @return bool
     */
    public function save(): bool
    {
        $result = false;

        if(!empty($this->street) && !empty($this->city) && !empty($this->zip)){
            if(isset($this->id)){
                $stmt = $this->database->prepare('
                    UPDATE address 
                    SET street = :street, city = :city, zip = :zip
                    WHERE id = :id'
                );
                $stmt->bindParam('id', $this->id);
            }else{
                $stmt = $this->database->prepare('
                    INSERT INTO address (user_id, street, city, zip) 
                    VALUES (:user_id, :street, :city, :zip)'
                );
                $stmt->bindParam('user_id', $this->user_id);
            }

            $stmt->bindParam('street', $this->street);
            $stmt->bindParam('city', $this->city);
            $stmt->bindParam('zip', $this->zip);
            $result = $stmt->execute();

            if(!isset($this->id) && $result){
                $this->id = $this->database->lastInsertId();
            }
        }else if(isset($this->id)){
            $result = AddressModel::removeAddressById($this->id, $this->database);
        }

        return $result;
    }

    public static function removeAddressById($id, \PDO $database): bool
    {
        $stmt = $database->prepare('DELETE FROM address WHERE id = :id');
        $stmt->bindParam('id', $id);


        return $stmt->execute();
    }

    public static function loadAddressByUserId(int $user_id, \PDO $database) : AddressModel|false{
        $stmt = $database->prepare('SELECT * FROM address WHERE user_id=:user_id');
        $stmt->bindParam('user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchObject(AddressModel::class);
    }

    public static function loadAddressById(int $id, \PDO $database) : AddressModel|false{
        $stmt = $database->prepare('SELECT * FROM address WHERE id=:id');
        $stmt->bindParam('id', $id);
        $stmt->execute();

        return $stmt->fetchObject(AddressModel::class);
    }

    public function createAddress(
        int $user_id,
        string $street,
        string $city,
        string $zip,
    ): void
    {
        $this->setUserId($user_id);
        $this->setStreet($street);
        $this->setCity($city);
        $this->setZip($zip);
    }

    public function getId(){
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return void
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return void
     */
    public function setStreet(string $street): void
    {
        $this->street = htmlspecialchars($street, ENT_QUOTES, 'UTF-8');;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');
    }

    public function getZip(): int
    {
        return $this->zip;
    }

    /**
     * @param ?string $zip
     * @return void
     * @throws Exception
     */
    public function setZip(?string $zip): void
    {
        if (!isset($zip)) return;
        $zip = str_replace(' ', '', $zip);
        if(ctype_digit($zip)){
            $this->zip = (int)$zip;
        }else{
            throw new Exception('Zip number is not valid');
        }
    }

    public function getAddressString(){
        return $this->street.'<br>'.$this->zip.' '.$this->city;
    }
}