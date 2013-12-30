<?php
namespace Brosland\Security;

use Nette\Security\Permission;

class Authorizator extends Permission
{
	public function __construct()
    {
        //roles
        $this->addRole('guest');
        $this->addRole('admin');
		
        // privileges
        $this->allow('admin',  Permission::ALL, Permission::ALL);
    }
}