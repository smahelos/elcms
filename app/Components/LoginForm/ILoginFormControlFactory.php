<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 25. 10. 2017
 * Time: 13:46
 */


interface ILoginFormControlFactory
{
    /** @return \LoginFormControl */
    public function create();
}