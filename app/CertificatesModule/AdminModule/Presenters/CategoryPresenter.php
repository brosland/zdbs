<?php
namespace CertificatesModule\AdminModule;

use CertificatesModule\AdminModule\Forms\CategoryForm,
	CertificatesModule\Models\Category\CategoryEntity,
	Kdyby\Doctrine\EntityDao,
	Nette\Forms\Controls\SubmitButton;

class CategoryPresenter extends \Brosland\Application\UI\SecurityPresenter
{
	/**
	 * @var EntityDao
	 */
	private $categoryDao;
	/**
	 * @var CategoryEntity
	 */
	private $categoryEntity = NULL;


	public function startup()
	{
		parent::startup();

		$this->categoryDao = $this->context->getService('certificates.categoryDao');
	}

	public function actionAdd()
	{
		$this->setView('edit');

		$categoryForm = $this['categoryForm'];
		$categoryForm['save']->onClick[] = callback($this, 'addCategory');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function addCategory(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();

		$this->categoryEntity = new CategoryEntity(
			$values->name, $values->codePrefix);
		$this->categoryEntity->setDescription($values->description);

		$this->categoryDao->save($this->categoryEntity);

		$this->flashMessage('Kategória certifikátov bola úspešne pridaná.', 'success');
		$this->redirect('list');
	}

	public function actionEdit()
	{
		$categoryForm = $this['categoryForm'];
	}

	///**
	// * @param SubmitButton $button
	// */
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
////			if ()
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
	 * @return CategoryForm
	 */
	protected function createComponentCategoryForm()
	{
		return new CategoryForm($this->categoryDao);
	}
}