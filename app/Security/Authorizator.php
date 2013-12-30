<?php
namespace Security;

use Nette\Security\Permission;

class Authorizator extends Permission
{
	public function __construct()
    {
        //roles
        $this->addRole('guest');
        $this->addRole('admin');
		
		// resource
		$this->addResource('administration');
		
        // privileges
        $this->allow('admin',  Permission::ALL, Permission::ALL);
    }
}