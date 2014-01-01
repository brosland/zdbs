<?php
namespace Brosland\Security;

use Brosland\Model\Entity,
	Doctrine\Common\Collections\ArrayCollection,
	Doctrine\ORM\Mapping as ORM,
	Nette\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class UserEntity extends Entity
{
	/**
	 * @ORM\Column
	 * @var string
	 */
	private $name;
	/**
	 * @ORM\Column
	 * @var string
	 */
	private $surname;
	/**
	 * @ORM\Column(unique=TRUE)
	 * @var string
	 */
	private $email;
	/**
	 * @ORM\Column(length=64)
	 * @var string
	 */
	private $password;
	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var DateTime
	 */
	private $registered;
	/**
	 * @ORM\Column(type="datetime", nullable=TRUE)
	 * @var DateTime
	 */
	private $lastLog;
	/**
	 * @ORM\ManyToMany(targetEntity="Brosland\Security\RoleEntity")
	 * @ORM\JoinTable(
	 *	name="user_roles",
	 * 	joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 * 	inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
	 * )
	 */
	private $roles;


	/**
	 * @param string $name
	 * @param string $surname
	 * @param string $email
	 * @param string $password
	 */
	public function __construct($name, $surname, $email, $password)
	{
		$this->name = $name;
		$this->surname = $surname;
		$this->email = $email;
		$this->password = $password;
		$this->registered = new DateTime();
		$this->roles = new ArrayCollection();
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
	public function getSurname()
	{
		return $this->surname;
	}

	/**
	 * @return string $surname
	 */
	public function setSurname($surname)
	{
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getFullname()
	{
		return $this->name . ' ' . $this->surname;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return \DateTime
	 */
	public function getRegistered()
	{
		return $this->registered;
	}

	/**
	 * @return DateTime
	 */
	public function getLastLog()
	{
		return $this->lastLog;
	}

	/**
	 * @param DateTime $lastLog
	 */
	public function setLastLog(DateTime $lastLog)
	{
		$this->lastLog = $lastLog;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getRoles()
	{
		return $this->roles;
	}
}