<?php
namespace CertificatesModule\AdminModule\Forms;

use Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateTypeSelectForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;


	/**
	 * @param EntityDao $certificateTypeDao
	 */
	public function __construct(EntityDao $certificateTypeDao)
	{
		parent::__construct();

		$this->certificateTypeDao = $certificateTypeDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$this->addEntitySelect('certificateType', 'Typ certifikátu', NULL, 'name')
			->setItems($this->certificateTypeDao->findAll())
			->setPrompt('Vyberte typ certifikátu')
			->setRequired();

		$this->addSubmit('continue', 'Ďalej');
	}
}