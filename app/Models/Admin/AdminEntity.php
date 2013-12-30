<?php
namespace Models\Admin;

use Brosland\Model\Entity,
	Brosland\Security\UserEntity,
	Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Admin")
 */
class AdminEntity extends Entity
{
	/**
	 * @ORM\OneToOne(targetEntity="Brosland\Security\UserEntity")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;


	/**
	 * @param UserEntity $user
	 */
	public function __construct(UserEntity $user)
	{
		$this->user = $user;
	}

	/**
	 * @return UserEntity
	 */
	public function getUser()
	{
		return $this->user;
	}
}