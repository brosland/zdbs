<?php
namespace FrontModule\Forms;

use Brosland\Application\UI\Form;

class SignForm extends Form
{
	/**
	 * @param \Nette\Application\IPresenter $presenter
	 */
	protected function configure(\Nette\Application\IPresenter $presenter)
	{
		$this->addProtection('Vypršala platnosť bezpečnostného kľúča pre tento formulár.');
		$this->addHidden('backlink');
		
		$this->addText('email', 'E-mail', 32, 64)
			->setEmptyValue('@')
			->setRequired()
			->addRule(self::EMAIL);
		$this->addPassword('password', 'Heslo', 32, 64)
			->setRequired();
		$this->addCheckbox('remember', 'Zapamätať prihlásenie');
		$this->addSubmit('signIn', 'Prihlásiť');
	}
}