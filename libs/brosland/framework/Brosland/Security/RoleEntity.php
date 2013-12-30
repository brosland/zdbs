<?php
namespace Brosland\Security;

use Brosland\Model\Entity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Role")
 */
class RoleEntity extends Entity
{
	/**
	 * @ORM\Column(length=64, unique=TRUE)
	 * @var string
	 */
	private $name;
	/**
	 * @ORM\Column(length=64, unique=TRUE)
	 * @var string
	 */
	private $label;


	/**
	 * @param string $name
	 * @param string $label
	 */
	public function __construct($name, $label)
	{
		$this->name = $name;
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}
}