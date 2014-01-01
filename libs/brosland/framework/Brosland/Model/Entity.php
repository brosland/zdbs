<?php
namespace Brosland\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class Entity extends \Nette\Object implements IEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	protected $id;
	
	
	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}