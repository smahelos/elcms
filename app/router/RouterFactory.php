<?php

namespace App;

use \Nette;
use \Nette\Application\Routers\Route;
use \Nette\Application\Routers\RouteList;

/**
 * Router factory.
 */
class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

        // Admin
        $router[] = new Route('admin/[<locale=cs cs|en>/]<presenter>/<action>/<id>', array(
            'module' => 'Admin',
            'presenter' => 'Admin',
            'action' => 'default',
            'id' => NULL,
        ));

        // Front
        $router[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>/<id>', array(
            'module' => 'Front',
            'presenter' => 'Homepage',
            'action' => 'default',
            'id' => NULL,
        ));

		return $router;
	}
}
