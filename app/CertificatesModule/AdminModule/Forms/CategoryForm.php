<?php
namespace CertificatesModule\AdminModule\Forms;

use CertificatesModule\Models\Category\CategoryEntity,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CategoryForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var EntityDao
	 */
	private $categoryDao;


	/**
	 * @param EntityDao $categoryDao
	 * @param CategoryEntity $categoryEntity
	 */
	public function __construct(EntityDao $categoryDao, CategoryEntity $categoryEntity = NULL)
	{
		parent::__construct();

		$this->categoryDao = $categoryDao;
		$this->entity = $categoryEntity;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$categoryDao = $this->categoryDao;
		$entity = $this->entity;
		
		$this->addText('name', 'Názov', 64, 255)
			->setRequired()
			->addRule(function($control) use($categoryDao, $entity) {
				$name = $control->getValue();
				return $entity !== NULL && $name === $entity->getName()
					|| !$categoryDao->findOneBy(array('name' => $name));
			}, 'Kategória s rovnakým názvom už existuje.');
			
		$this->addText('codePrefix', 'Prefix', 64, 64)
			->setRequired()
			->addRule(function($control) use($categoryDao, $entity) {
				$codePrefix = $control->getValue();
				return $entity !== NULL && $codePrefix === $entity->getCodePrefix()
					|| !$categoryDao->findOneBy(array('codePrefix' => $codePrefix));
			}, 'Kategória s rovnakým prefixom už existuje.');	
			
		$this->addTextArea('description', 'Popis');

		$this->addSubmit('save', 'Ulož');
		
		if ($this->entity)
		{
			$this->bindEntity($this->entity);
		}
	}
}