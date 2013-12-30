<?php
namespace FrontModule\Forms;

use Brosland\Application\UI\Form,
	Kdyby\Doctrine\EntityDao;

class ForgotPasswordForm extends Form
{
	/** @var EntityDao */
	private $userDao;
	
	
	/**
	 * @param EntityDao $userDao
	 */
	public function __construct(EntityDao $userDao)
	{
		parent::__construct();
		
		$this->userDao = $userDao;
	}

	/**
	 * @param \Nette\Application\IPresenter $presenter
	 */
	protected function configure(\Nette\Application\IPresenter $presenter)
	{
		$userDao = $this->userDao;
		
		$this->addProtection('Vypršala platnosť bezpečnostného kľúča pre tento formulár.');
		$this->addAntispam();
		$this->addText('email', 'E-mail', 32, 64)
			->setRequired()
			->setDefaultValue('@')
			->addRule(self::EMAIL)
			->addRule(function($control) use($userDao) {
				return $userDao->findOneBy(array('email' => $control->getValue()));
			}, 'Užívateľ s e-mailom "%value" neexistuje.');
		$this->addSubmit('send', 'Odoslať');
	}
}