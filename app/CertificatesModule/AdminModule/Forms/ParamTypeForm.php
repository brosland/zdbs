<?php
namespace CertificatesModule\AdminModule\Forms;

use CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\ParamType\ParamType,
	Nette\Application\IPresenter;

class ParamTypeForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var array
	 */
	private static $REQUIRED_OPTIONS = array(
		TRUE => 'Áno', FALSE => 'Nie'
	);
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity;
	/**
	 * @var EntityDao
	 */
	private $paramTypeDao;


	/**
	 * @param CertificateTypeEntity $certificateTypeEntity
	 * @param EntityDao $paramTypeDao
	 */
	public function __construct(CertificateTypeEntity $certificateTypeEntity, EntityDao $paramTypeDao)
	{
		parent::__construct();

		$this->certificateTypeEntity = $certificateTypeEntity;
		$this->paramTypeDao = $paramTypeDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$this->addText('name', 'Názov premennej', 64, 255)
			->setRequired();
		$this->addText('label', 'Názov', 64, 255)
			->setRequired();
		$this->addTextArea('description', 'Popis');
		$this->addText('order', 'Priorita zobrazenia v editačnom formulári certifikátu', 8)
			->setRequired()
			->addRule(self::INTEGER)
			->setDefaultValue(0);
		$this->addRadioList('required', 'Vyžadovaný', self::$REQUIRED_OPTIONS)
			->setRequired();
		$this->addSelect('paramTypeId', 'Typ premennej', ParamType::getValues())
			->setRequired()
			->setPrompt('Vyberte možnosť');
	}
}