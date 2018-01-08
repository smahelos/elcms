<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 28. 11. 2017
 * Time: 15:18
 */

namespace App\Models;

use \Nette\Security\Permission;

class AuthorizatorFactory
{
    /**
     * @throws \Nette\InvalidArgumentException
     * @throws \Nette\InvalidStateException
     *
     * @return Permission
     */
    public static function create(): Permission
    {
        $acl = new Permission;

        // pokud chceme, můžeme role a zdroje načíst z databáze
        $acl->addRole('admin');
        $acl->addRole('guest');

        $acl->addResource('backend');

        $acl->allow('admin', 'backend');
        $acl->deny('guest', 'backend');

        // případ A: role admin má menší váhu než role guest
        $acl->addRole('john', ['admin', 'guest']);
        $acl->isAllowed('john', 'backend'); // false

        // případ B: role admin má větší váhu než guest
        $acl->addRole('mary', ['guest', 'admin']);
        $acl->isAllowed('mary', 'backend'); // true

        return $acl;
    }
}