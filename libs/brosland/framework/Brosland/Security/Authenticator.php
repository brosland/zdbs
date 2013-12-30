<?php
namespace Brosland\Security;

use Kdyby\Doctrine\EntityDao,	
	Nette\Object,
	Nette\Security as NS;

class Authenticator extends Object implements NS\IAuthenticator
{
	/** @var EntityDao */
	private $userDao;
	/** @var string */
	private $salt;
	
	
	/**
	 * @param EntityDao $userDao
	 * @param string $salt
	 */
	public function __construct(EntityDao $userDao, $salt)
	{
		$this->userDao = $userDao;
		$this->salt = $salt;
	}
	
	/**
	 * Performs an authentication
	 * @param array $credentials
	 * @return NS\Identity
	 * @throws NS\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		$user = $this->userDao->findOneBy(array('email' => $email));
		
		if(!$user)
		{
			throw new NS\AuthenticationException('Používateľ s emailom "' . $email . '" neexistuje.', self::IDENTITY_NOT_FOUND);
		}
		
		if($user->password !== $this->calculateHash($password))
		{
			throw new NS\AuthenticationException('Nesprávne heslo.', self::INVALID_CREDENTIAL);
		}
		
		$user->setLastLog(new \DateTime('now'));
		$this->userDao->save($user);
		
		$roles = array_map(function(Role $role) {
			return $role->name;
		}, $user->getRoles()->getValues());
		
		return new NS\Identity($user->id, $roles, array(
			'name' => $user->name,
			'surname' => $user->surname,
			'email' => $user->email,
		));
	}
	
	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public function calculateHash($password)
	{
		return sha1($password . $this->salt);
	}
}