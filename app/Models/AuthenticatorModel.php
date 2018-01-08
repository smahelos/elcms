<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 10:33
 */

use Nette\Security as NS;

/**
 * Class AuthenticatorModel
 */
class AuthenticatorModel implements NS\IAuthenticator
{
    /**
     * @var \Nette\Database\Context
     */
    public $database;

    /**
     * AuthenticatorModel constructor.
     *
     * @param \Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * @param array $credentials
     *
     * @return NS\Identity
     * @throws NS\AuthenticationException
     */
    public function authenticate(array $credentials): NS\Identity
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('users')
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new NS\AuthenticationException('User not found.');
        }

        if (!NS\Passwords::verify($password, $row->password)) {
            throw new NS\AuthenticationException('Invalid password.');
        }

        return new NS\Identity($row->id, $row->role, ['username' => $row->username]);
    }
}