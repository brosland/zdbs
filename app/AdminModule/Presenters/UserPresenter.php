<?php
namespace AdminModule;

use AdminModule\Components\UserTable\UserTable;

class UserPresenter extends BasePresenter
{
	public function actionRegister()
	{
//		$authenticator = $this->getService('authenticator');
//		$password = $authenticator->calculateHash('heslo');
//		
//		$users = new \Doctrine\Common\Collections\ArrayCollection();
//		$users->add(new \Brosland\Security\User('John', 'Doe', 'john@doe.com', $password));
	}
	
	/**
	 * @return UserTable
	 */
	protected function createComponentUserTable()
	{
		return new UserTable($this->getService('userDao'));
	}
}