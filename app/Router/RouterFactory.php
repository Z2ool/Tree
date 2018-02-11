<?php
/**
 * @author Petr Klezla
 * @date 10.02.2018
 */
namespace App\Router;


use App\Model;
use Nette,
    Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

    /**
     * @return \Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList();
        $router[] = new Route('install/', 'Install:default');
        $router[] = new Route('<action>[/<id>]/', 'Tree:default');
        return $router;
    }
}
