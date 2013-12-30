<?php
namespace CertificatesModule\AdminModule;

use CertificatesModule\AdminModule\Forms\CertificateTypeForm,
	CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Kdyby\Doctrine\EntityDao,
	Nette\Forms\Controls\SubmitButton;

class CertificateTypePresenter extends \Brosland\Application\UI\SecurityPresenter
{
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity = NULL;


	public function startup()
	{
		parent::startup();

		$this->certificateTypeDao = $this->context->getService('certificates.certificateTypeDao');
	}

	public function actionAdd()
	{
		$this->setView('edit');

		$certificateTypeForm = $this['certificateTypeForm'];
		$certificateTypeForm['save']->onClick[] = callback($this, 'addCertificateType');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function addCertificateType(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();

		$this->certificateTypeEntity = new CertificateTypeEntity(
			$values->category, $values->name, $values->template);
		$this->certificateTypeEntity->setDescription($values->description);

		for ($i = 0; $i < count($values->paramTypes); $i++)
		{
			$paramType = $values->paramTypes[$i];
			$paramTypeEntity = new ParamTypeEntity($paramType->name,
				$paramType->label, $paramType->paramTypeId, $this->certificateTypeEntity);
			$paramTypeEntity->setOrder($i)
				->setDescription($paramType->description)
				->setRequired($paramType->required);

			$this->certificateTypeEntity->getParamTypes()->add($paramTypeEntity);
		}

		$this->certificateTypeDao->save($this->certificateTypeEntity);

		$this->flashMessage('Typ certifikátu bol úspešne pridaný.', 'success');
		$this->redirect('list');
	}

	public function actionEdit()
	{
		$certificateTypeForm = $this['certificateTypeForm'];
//		$certificateTypeForm['save']->onClick[] = callback($this, 'editCertificateType');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function editCertificateType(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();

		$this->certificateTypeEntity->setName($values->name)
			->setCategory($values->category)
			->setTemplate($values->template)
			->setDescription($values->description);

		$paramTypes = $this->certificateTypeDao->related('paramTypes')
			->findAssoc(array('certificateType' => $this->certificateTypeEntity), 'id');

		for ($i = 0; $i < count($values->paramTypes); $i++)
		{
			$paramType = $values->paramTypes[$i];

//			if ()

			$paramTypeEntity = new ParamTypeEntity($paramType->name, $paramType->label, $paramType->paramTypeId, $this->certificateTypeEntity);
			$paramTypeEntity->setOrder($i)
				->setDescription($paramType->description)
				->setRequired($paramType->required);

			$this->certificateTypeEntity->getParamTypes()->add($paramTypeEntity);
		}

		$certificateType->setDescription($description);

		$this->flashMessage('Typ certifikátu bol úspešne pridaný.', 'success');
		$this->redirect('list');
	}

	/**
	 * @return CertificateTypeForm
	 */
	protected function createComponentCertificateTypeForm()
	{
		$form = new CertificateTypeForm($this->certificateTypeDao);
		return $form;
	}
}