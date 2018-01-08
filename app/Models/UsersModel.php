<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 10:55
 */

namespace App\Models;

use \Nette,
    \Nette\Security\Passwords;

/**
 * Class UsersModel
 * @package App\Models
 */
class UsersModel
{
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    /** @var $table */
    private $table;

    /** @var $userSalt */
    public static $userSalt = 'AEcx199opQ';

    /**
     * UsersModel constructor.
     *
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->table = 'users';
    }

    /**
     * @param $values
     *
     * @return bool|int|\Nette\Database\Table\IRow
     */
    public function insertUser($values)
    {
        return $this->database->table($this->table)->insert($values);
    }

    /**
     * @param $userId
     * @param $values
     */
    public function updateUser($userId, $values)
    {
        $this->database->table($this->table)
            ->where('id', $userId)
            ->update([
                'name' => $values->name,
                'surname' => $values->surname,
                'username' => $values->username,
                'role' => $values->role,
                'email' => $values->email,
                'password' => Passwords::hash($values->password),
                'phone' => $values->phone,
                'mobilephone' => $values->mobilephone,
                'fax' => $values->fax,
                'photo' => $values->photo,
                'website' => $values->website,
                'city' => $values->city,
                'address' => $values->address,
                'zip' => $values->zip,
                'state' => $values->state,
                'lastlogin' => time(),
            ]);
    }

    /**
     * @return Nette\Database\Table\Selection
     */
    public function findAll(): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)->select('*');
    }

    /**
     * @param $userId
     *
     * @return Nette\Database\Table\Selection
     */
    public function findOneById($userId): Nette\Database\Table\Selection
    {
        return $this->database->table($this->table)->where('id', $userId);
    }

    /**
     * @param $id
     *
     * @return Nette\Database\Table\IRow
     */
    public function getUserById($id): Nette\Database\Table\IRow
    {
        return $this->database->table($this->table)->get($id);
    }
}