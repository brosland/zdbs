<?php
namespace CertificatesModule\AdminModule\Forms;

use Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter,
	Nette\Forms\Controls\BaseControl;

class CategoryForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var EntityDao
	 */
	private $categoryDao;


	/**
	 * @param EntityDao $categoryDao
	 */
	public function __construct(EntityDao $categoryDao)
	{
		parent::__construct();

		$this->categoryDao = $categoryDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$categoryDao = $this->categoryDao;

		$this->addText('name', 'Názov', 64, 255)
			->setRequired()
			->addRule(function(BaseControl $control) use($categoryDao) {
				$form = $control->getForm();
				$name = $control->getValue();
				return $form->hasEntity() && $name === $form->getEntity()->getName()
					|| !$categoryDao->findOneBy(array('name' => $name));
			}, 'Kategória s rovnakým názvom už existuje.');

		$this->addText('codePrefix', 'Prefix', 64, 64)
			->setRequired()
			->addRule(function(BaseControl $control) use($categoryDao) {
				$form = $control->getForm();
				$codePrefix = $control->getValue();
				return $form->hasEntity() && $codePrefix === $form->getEntity()->getCodePrefix()
					|| !$categoryDao->findOneBy(array('codePrefix' => $codePrefix));
			}, 'Kategória s rovnakým prefixom už existuje.');	

		$this->addTextArea('description', 'Popis');
		$this->addSubmit('save', 'Ulož');
	}
}