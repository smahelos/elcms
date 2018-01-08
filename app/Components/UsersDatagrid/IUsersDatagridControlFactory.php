<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 8. 11. 2017
 * Time: 11:43
 */

interface IUsersDatagridControlFactory
{
    /** @return \UsersDatagridControl */
    public function create();
}