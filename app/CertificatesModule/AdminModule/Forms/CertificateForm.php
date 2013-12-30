<?php
namespace CertificatesModule\AdminModule\Forms;

use Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateForm extends \Brosland\Application\UI\EntityForm
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
		$this->addText('name', 'Názov premennej', 64, 255)
			->setRequired();
		$this->addTextArea('description', 'Popis');
		$this->addTextArea('template', 'Šablóna výpisu detailu');
		$this->addEntitySelect('certificateTypeCategory', 'Typ certifikátu', NULL, 'name')
			->setItems($this->categoryDao->findAll())
			->setPrompt('Vyberte kategóriu')
			->setRequired();
	}
}