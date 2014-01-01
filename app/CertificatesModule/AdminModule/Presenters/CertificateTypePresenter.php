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


	public function startup()
	{
		parent::startup();

		$this->certificateTypeDao = $this->context->getService('certificates.certificateTypeDao');
	}
	
	public function actionList()
	{
		throw new \Nette\Application\BadRequestException('Not supported yet.');
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

		$certificateTypeEntity = new CertificateTypeEntity(
			$values->category, $values->name, $values->template);
		$certificateTypeEntity->setDescription($values->description);

		for ($i = 0; $i < count($values->paramTypes); $i++)
		{
			$paramType = $values->paramTypes[$i];
			$paramTypeEntity = new ParamTypeEntity($paramType->name, $paramType->label,
				$paramType->paramTypeId, $this->certificateTypeEntity);
			$paramTypeEntity->setOrdering($i)
				->setRequired($paramType->required);

			$certificateTypeEntity->getParamTypes()->add($paramTypeEntity);
		}

		$this->certificateTypeDao->save($certificateTypeEntity);
		$this->certificateTypeDao->getEntityManager()->flush();

		$this->flashMessage('Typ certifikátu bol úspešne pridaný.', 'success');
		$this->redirect('list');
	}

	public function actionEdit()
	{
		throw new \Nette\Application\BadRequestException('Not supported yet.');
//		$certificateTypeForm = $this['certificateTypeForm'];
//		$certificateTypeForm['save']->onClick[] = callback($this, 'editCertificateType');
	}

//	/**
//	 * @param SubmitButton $button
//	 */
//	public function editCertificateType(SubmitButton $button)
//	{
//		$values = $button->getForm()->getValues();
//
//		$this->certificateTypeEntity->setName($values->name)
//			->setCategory($values->category)
//			->setTemplate($values->template)
//			->setDescription($values->description);
//
//		$paramTypes = $this->certificateTypeDao->related('paramTypes')
//			->findAssoc(array('certificateType' => $this->certificateTypeEntity), 'id');
//
//		for ($i = 0; $i < count($values->paramTypes); $i++)
//		{
//			$paramType = $values->paramTypes[$i];
//
//			$paramTypeEntity = new ParamTypeEntity($paramType->name, $paramType->label, $paramType->paramTypeId, $this->certificateTypeEntity);
//			$paramTypeEntity->setOrder($i)
//				->setDescription($paramType->description)
//				->setRequired($paramType->required);
//
//			$this->certificateTypeEntity->getParamTypes()->add($paramTypeEntity);
//		}
//
//		$certificateType->setDescription($description);
//
//		$this->flashMessage('Typ certifikátu bol úspešne pridaný.', 'success');
//		$this->redirect('list');
//	}

	/**
	 * @return CertificateTypeForm
	 */
	protected function createComponentCertificateTypeForm()
	{
		return new CertificateTypeForm($this->certificateTypeDao);
	}
}