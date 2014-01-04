<?php
namespace FrontModule;

use FrontModule\Forms\SignForm,
	Nette\Security\AuthenticationException;

class SignPresenter extends \Presenters\BasePresenter
{

	/**
	 * @var string|NULL $backlink
	 */
	public function actionIn($backlink = NULL)
	{
		if ($this->getUser()->isLoggedIn())
		{
			$this->redirect('Homepage:');
		}

		$this['signForm']['backlink']->setValue($backlink);
	}

	public function actionOut()
	{
		$this->user->logout();

		$this->flashMessage('Boli ste úspešne odhlásení.', 'warning');
		$this->redirect('in');
	}

	/**
	 * @param SignForm
	 */
	public function signFormSubmitted(SignForm $form)
	{
		$values = $form->getValues();

		try
		{
			if ($values->remember)
			{
				$this->getUser()->setExpiration('+ 14 days', FALSE);
			}
			else
			{
				$this->getUser()->setExpiration('+ 20 minutes', TRUE);
			}

			$this->getUser()->login($values->email, $values->password);

			if ($values->backlink)
			{
				$this->getApplication()->restoreRequest($values->backlink);
			}

			$this->redirect('Homepage:');
		}
		catch (AuthenticationException $e)
		{
			$form->addError('Nesprávne meno alebo heslo!');
		}
	}

	/**
	 * @return SignForm
	 */
	protected function createComponentSignForm()
	{
		$form = new SignForm();
		$form->onSuccess[] = callback($this, 'signFormSubmitted');

		return $form;
	}
}