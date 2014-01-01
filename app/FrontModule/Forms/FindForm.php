<?php
namespace FrontModule\Forms;

use Brosland\Application\UI\Form,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class FindForm extends Form
{
	/**
	 * @var EntityDao
	 */
	private $certificateDao;

	
	/**
	 * @param EntityDao $categoryDao
	 */
	public function __construct(EntityDao $certificateDao)
	{
		parent::__construct();

		$this->certificateDao = $certificateDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$certificateDao = $this->certificateDao;
		
		$this->addText('find', 'Vyhľadaj', 64, 255)
			->setRequired();

		$this->addSubmit('save', 'Vyhľadaj');
	}
}