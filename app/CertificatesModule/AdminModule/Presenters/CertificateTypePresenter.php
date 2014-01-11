<?php
namespace CertificatesModule\AdminModule;

use CertificatesModule\AdminModule\Components\CertificateTypeTable,
	CertificatesModule\AdminModule\Forms\CertificateTypeForm,
	CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	Nette\Forms\Controls\SubmitButton;

class CertificateTypePresenter extends \Presenters\BasePresenter
{
	/**
	 * @autowire(CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	 * 	factory=Kdyby\Doctrine\EntityDaoFactory)
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $certificateTypeDao;


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
			$paramTypeEntity = new ParamTypeEntity($paramType->name, $paramType->label, $paramType->paramTypeId, $certificateTypeEntity);
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

	/**
	 * @return CertificateTypeTable
	 */
	protected function createComponentCertificateTypeTable()
	{
		$queryBuilder = $this->certificateTypeDao->createQueryBuilder('certificateType')
			->leftJoin('certificateType.category', 'category')
			->groupBy('certificateType.id');

		return new CertificateTypeTable($this->certificateTypeDao, $queryBuilder);
	}
}