<?php
namespace Brosland\Model;

interface IEntity
{
	/**
	 * Access to reflection.
	 * @return \Nette\Reflection\ClassType
	 */
	public /**/static/**/ function getReflection();
	
	/**
	 * @return int
	 */
	public function getId();
}