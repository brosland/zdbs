<?php
namespace CertificatesModule\AdminModule\Forms;

use Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class ImportCertificatesForm extends \Brosland\Application\UI\EntityForm
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
		
		$this->addUpload('file', 'XML súbor')
			->addRule(function($control) {
					return $control->value->isOk();
				}, 'Počas prenosu došlo k poškodeniu súboru.')
				->addRule(function($control) {
					$types = array(
						'text/xml',
						'application/xml',
						'application/octet-stream'
					);
					
					return $control->value->isOk()
						&& in_array($control->getValue()->getContentType(), $types);
				}, 'Vložený súbor nie vo formáte XML.')
			->setRequired();

		$this->addSubmit('import', 'Importovať');
	}
}