<?php
namespace FrontModule\Forms;

use Brosland\Application\UI\EntityForm,
	Brosland\Model\IEntity,
	Brosland\Security\Authenticator,
	Kdyby\Doctrine\EntityDao;

class UserForm extends EntityForm
{
	/** @var Authenticator */
	private $authenticator;
	/** @var EntityDao */
	private $userDao;
	/** @var IEntity */
	private $userEntity;
	
	
	/**
	 * @param Authenticator $authenticator
	 * @param EntityDao $userDao
	 * @param IEntity $userEntity
	 */
	public function __construct(Authenticator $authenticator, EntityDao $userDao, IEntity $userEntity)
	{
		parent::__construct();
		
		$this->authenticator = $authenticator;
		$this->userDao = $userDao;
		$this->userEntity = $userEntity;
	}

	/**
	 * @param \Nette\Application\IPresenter $presenter
	 */
	protected function configure(\Nette\Application\IPresenter $presenter)
	{
		$authenticator = $this->authenticator;
		$userDao = $this->userDao;
		$userEntity = $this->userEntity;
		
		$this->addProtection('Vypršala platnosť bezpečnostného kľúča pre tento formulár.');
		$this->addText('name', 'Meno', 32, 64)
			->setRequired();
		$this->addText('surname', 'Priezvisko', 32, 64)
			->setRequired();
		$this->addText('email', 'E-mail', 32, 64)
			->setRequired()
			->setEmptyValue('@')
			->addRule(self::EMAIL)
			->addRule(function($control) use($userDao, $userEntity) {
				$email = $control->getValue();
				return $email === $userEntity->getEmail()
					|| !$userDao->findOneBy(array('email' => $email));
			}, 'E-mail "%value" je už obsadený.');
		$this->addPassword('oldPassword', 'Aktuálne heslo', 32, 64)
			->addRule(function($control) use($authenticator, $userEntity) {
				$password = $authenticator->calculateHash($control->getValue());
				return $userEntity->getPassword() === $password;
			}, 'Zadané aktuálne heslo je nesprávne!')
			->setRequired();
		$password = $this->addPassword('password', 'Nové heslo', 32, 64)
			->setRequired();
		$this->addPassword('password2', 'Overenie nového hesla', 32, 64)
			->addRule(function($control) use($password) {
				return $control->getValue() == $password->getValue();
			}, 'Heslo sa nezhoduje s overovacím heslom.');

		$this->addSubmit('save', 'Uložiť');
	}
}