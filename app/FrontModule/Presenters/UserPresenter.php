<?php
namespace FrontModule;

use Kdyby\Doctrine\EntityDao,
	Brosland\Security\Authenticator,
	Brosland\Security\User,
	FrontModule\Forms\UserForm,
	FrontModule\Forms\ForgotPasswordForm,
	Nette\Application\BadRequestException,
	Nette\Mail\Message;

class UserPresenter extends \Presenters\BasePresenter
{
	/** @var User */
	private $userEntity = NULL;


	/**
	 * @return EntityDao
	 */
	private function getUserDao()
	{
		return $this->getService('userDao');
	}

	/**
	 * @return Authenticator
	 */
	private function getAuthenticator()
	{
		return $this->getService('authenticator');
	}

	/**
	 * @throw BadRequestException
	 */
	public function actionEdit()
	{
		if (!$this->user->isLoggedIn())
		{
			$this->checkAccessDeniedReason();
		}
		else if (!$this->userEntity = $this->getUserDao()->find($this->getUser()->getId()))
		{
			throw new BadRequestException('Používateľ sa nenašiel.', 404);
		}

		$this['userForm']->bindEntity($this->userEntity);
		$this['userForm']['password']->setDefaultValue(NULL);
	}

	/**
	 * @return UserForm
	 */
	public function createComponentUserForm()
	{
		$form = new UserForm($this->getAuthenticator(), $this->getUserDao(), $this->userEntity);
		$form->onSuccess[] = callback($this, 'editProfile');

		return $form;
	}

	/**
	 * @param UserForm $form
	 */
	public function editProfile(UserForm $form)
	{
		$values = $form->getValues();

		$this->userEntity->setName($values->name);
		$this->userEntity->setSurname($values->surname);
		$this->userEntity->setEmail($values->email);
		$this->userEntity->setPassword($this->getAuthenticator()->calculateHash($values->password));

		$this->getUserDao()->save($this->userEntity);

		$this->user->getIdentity()->name = $this->userEntity->getName();
		$this->user->getIdentity()->surname = $this->userEntity->getSurname();
		$this->user->getIdentity()->email = $this->userEntity->getEmail();

		$this->flashMessage('Váš profil bol úspešne zmenený.', 'success');
		$this->redirect('Homepage:');
	}

	/**
	 * @return ForgotPasswordForm
	 */
	public function createComponentForgotPasswordForm()
	{
		$form = new ForgotPasswordForm($this->getUserDao());
		$form->onSuccess[] = callback($this, 'sendNewPassword');

		return $form;
	}

	/**
	 * @param ForgotPasswordForm $form
	 */
	public function sendNewPassword(ForgotPasswordForm $form)
	{
		$password = \Nette\Utils\Strings::random(8);

		$this->userEntity = $this->getUserDao()->findOneBy(array('email' => $form->getValues()->email));
		$this->userEntity->setPassword($this->getAuthenticator()->calculateHash($password));

		$mail = new Message();
		$mail->setFrom($this->context->parameters['page']['email'], $this->context->parameters['page']['name'])
			->addTo($this->userEntity->getEmail())
			->setSubject('Žiadosť o zmenu hesla')
			->setBody('Dobrý deň, na Vašu žiadosť Vám bolo zaslané nové prihlasovacie heslo: ' . $password)
			->send();

		$this->getUserDao()->save($this->userEntity);

		$this->flashMessage('Nové heslo bolo zaslané na Vami uvedenú e-mailovú adresu.', 'success');
		$this->redirect('Sign:in');
	}
}