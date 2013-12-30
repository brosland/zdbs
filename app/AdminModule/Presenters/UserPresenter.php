<?php
namespace AdminModule;

use AdminModule\Components\Tables\UserTable;

class UserPresenter extends BasePresenter
{
	public function actionRegister()
	{
//		$authenticator = $this->getService('authenticator');
//		$password = $authenticator->calculateHash('heslo');
//		
//		$users = new \Doctrine\Common\Collections\ArrayCollection();
//		$users->add(new \Brosland\Security\User('Katka', 'Lašáková', 'katarina.lasakova@netinvest.sk', $password));
//		$users->add(new \Brosland\Security\User('Peťo', 'Zúber', 'peter.zuber@netinvest.sk', $password));
//		$users->add(new \Brosland\Security\User('Ľuboš', 'Ďurovič', 'lubos.durovic@netinvest.sk', $password));
//		$users->add(new \Brosland\Security\User('Marek', 'Turčáni', 'marek.turcani@netinvest.sk', $password));
//		$users->add(new \Brosland\Security\User('Róbert', 'Vajda', 'robert.vajda@netinvest.sk', $password));
//		
//		$this->getUserDao()->save($users);
	}
	
	/**
	 * @return UserTable
	 */
	protected function createComponentUserTable()
	{
		return new UserTable($this->getService('userDao'));
	}
}