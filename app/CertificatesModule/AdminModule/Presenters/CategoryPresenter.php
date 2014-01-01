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

		$categoryEntity = new CategoryEntity($values->name, $values->codePrefix);
		$categoryEntity->setDescription($values->description);

		$this->categoryDao->save($categoryEntity);

		$this->flashMessage('Kategória certifikátov bola úspešne pridaná.', 'success');
		$this->redirect('list');
	}

	/**
	 * @var int $id
	 */
	public function actionEdit($id)
	{
		$categoryEntity = $this->categoryDao->find($id);
		
		if (!$categoryEntity)
		{
			throw new \Nette\Application\BadRequestException('Category not found.');
		}
		
		$categoryForm = $this['categoryForm'];
		$categoryForm->bindEntity($categoryEntity);
		$categoryForm['save']->onClick[] = callback($this, 'editCategory');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function editCategory(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();
		$categoryEntity = $button->getForm()->getEntity()
			->setName($values->name)
			->setCodePrefix($values->codePrefix)
			->setDescription($values->description);

		$this->categoryDao->save($categoryEntity);

		$this->flashMessage('Kategória certifikátov bola úspešne upravená.', 'success');
		$this->redirect('list');
	}

	/**
	 * @return CategoryForm
	 */
	protected function createComponentCategoryForm()
	{
		return new CategoryForm($this->categoryDao);
	}
}