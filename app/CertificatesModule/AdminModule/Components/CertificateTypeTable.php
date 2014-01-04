<?php
namespace CertificatesModule\AdminModule\Components;

use Brosland\Components\Table\Models\DoctrineModel,
	Brosland\Components\Table\Table,
	DateTime,
	Doctrine\ORM\QueryBuilder,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter,
	Nette\Http\Response,
	SimpleXMLElement;

class CertificateTypeTable extends Table
{
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;
	/**
	 * @var Response
	 */
	private $httpResponse;


	/**
	 * @param EntityDao $certificateTypeDao
	 * @param QueryBuilder $queryBuilder
	 */
	public function __construct(EntityDao $certificateTypeDao, QueryBuilder $queryBuilder, Response $httpResponse)
	{
		parent::__construct(new DoctrineModel($this, $queryBuilder));

		$this->certificateTypeDao = $certificateTypeDao;
		$this->httpResponse = $httpResponse;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$table = $this;

		// columns
		$this->addColumn('name', 'Názov');
		$this->addColumn('description', 'Popis')
			->maxLength = 64;
		$this->addColumn('category', 'Kategória', 'category.name');
		$this->addColumn('codePrefix', 'Prefix kódu', 'category.codePrefix');

		// actions
		$this->addAction('edit', 'Editovať')
			->setIcon('ui-icon-pencil')
			->setLink(callback(function($certificateType) use($presenter) {
					return $presenter->link('CertificateType:edit', $certificateType->id);
				}));
		$this->addAction('export', 'Export certifikátov')
			->setIcon('ui-icon-arrowthick-1-s')
			->setLink(callback(function($certificateType) use($table) {
					return $table->link('exportCertificates', $certificateType->id);
				}));
		$this->addAction('import', 'Import certifikátov')
			->setIcon('ui-icon-arrowthick-1-n')
			->setLink(callback(function($certificateType) use($table) {
					return $table->getPresenter()->link(':Certificates:Admin:Certificate:import', $certificateType->id);
				}));

		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteCertificateTypes');
		
		// sorting
		$this->setSortTypes(array(
			'name' => 'názvu',
			'description' => 'popisu',
			'codePrefix' => 'prefixu kódu',
			'category' => 'kategórie'
		));

		$this->setDefaultSorting(array('name' => 'asc'));
	}
	
	/**
	 * @param \Nette\Forms\Controls\SubmitButton
	 */
	public function deleteCertificateTypes(\Nette\Forms\Controls\SubmitButton $button)
	{
		$certificateTypes = $button->getForm()->getSelectedItems();

		if (!empty($certificateTypes))
		{
			$this->certificateTypeDao->delete($certificateTypes);
			$this->flashMessage('Typy certifikátov boli zmazané.', 'success');
		}
	}
	
	/**
	 * @param int $certificateTypeId
	 */
	public function handleExportCertificates($certificateTypeId)
	{
		$certificateType = $this->certificateTypeDao->find($certificateTypeId);
		
		if (!$certificateType)
		{
			throw new \Nette\Application\BadRequestException('Certficate type not found.' , 404);
		}
		
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
		$certificateTypeXML = $xml->addChild('certificateType');
		$certificateTypeXML->addAttribute('name', $certificateType->getName());
		
		foreach ($certificateType->getCertificates() as $certificate)
		/* @var $certificate \CertificatesModule\Models\Certificate\CertificateEntity */
		{
			$certificateXML = $certificateTypeXML->addChild('certificate');
			$certificateXML->addChild('code', $certificate->getCode());
			$certificateXML->addChild('created', $certificate->getCreated()->format(DateTime::W3C));
			$certificateXML->addChild('expiration', $certificate->hasExpiration() ?
				$certificate->getExpiration()->format(DateTime::W3C) : '');
			
			foreach ($certificate->getParams() as $param)
			/* @var $param \CertificatesModule\Models\Param\ParamEntity */
			{
				$certificateXML->addChild($param->getParamType()->getName(), $param);
			}
		}
		
		$dom = new \DOMDocument('1.0');
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		
		$fileName = $certificateType->getName() . '.xml';
		$response = new \Nette\Application\Responses\TextResponse($dom->saveXML());
		
		$this->httpResponse->setHeader('Content-Description', 'File Transfer')
			->setHeader('Content-Disposition', 'attachment; filename=' . $fileName)
			->setContentType('application/xml', 'UTF-8');
		
		$this->getPresenter()->sendResponse($response);
	}
}