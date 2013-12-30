<?php
namespace Router;

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;

class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new SimpleRouter('Front:Homepage:default', Route::ONE_WAY);
		
		// Certificates-admin module
		$router[] = $certificatesAdminRouter = new RouteList('Certificates:Admin');
		$certificatesAdminRouter[] = new Route('certificates/admin/<presenter>[/<action>]');
		$certificatesAdminRouter[] = new Route('certificates/admin/<presenter>/<action>[/<id [0-9]+>]');

		// Certificates module
		$router[] = $certificatesRouter = new RouteList('Certificates');
		$certificatesRouter[] = new Route('certificates/<presenter>[/<action>]');
		$certificatesRouter[] = new Route('certificates/<presenter>/<action>[/<id [0-9]+>]');
		
		// Admin module
		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('admin/<presenter>[/<action>]', 'Dashboard:default');
		$adminRouter[] = new Route('admin/<presenter>/<action>[/<id [0-9]+>]', 'Dashboard:default');

		// Front module
		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route('<presenter>/<action>[/<id [0-9]+>]', 'Homepage:default');
		
		return $router;
	}
}