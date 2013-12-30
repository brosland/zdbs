<?php
namespace CertificatesModule\AdminModule\Forms;

use CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamType,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity;
	/**
	 * @var EntityDao
	 */
	private $certificateDao;


	/**
	 * @param CertificateTypeEntity $certificateTypeEntity
	 * @param EntityDao $certificateDao
	 * @param CertificateEntity $certificateEntity
	 */
	public function __construct(CertificateTypeEntity $certificateTypeEntity,
		EntityDao $certificateDao, CertificateEntity $certificateEntity = NULL)
	{
		parent::__construct();

		$this->certificateTypeEntity = $certificateTypeEntity;
		$this->certificateDao = $certificateDao;
		$this->entity = $certificateEntity;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$this->addGroup('Certifikát');
		$this->addDatePicker('expiration', 'Dátum expirácie');
		
		$this->addGroup('Parametre certifikátu');
		
		$paramTypes = $this->certificateTypeEntity->getParamTypes();
		$params = $this->addContainer('params');
		
		foreach ($paramTypes as $paramType)
		/* @var $paramType \CertificatesModule\Models\ParamType\ParamTypeEntity */
		{
			$control = NULL;
			
			switch ($paramType->getParamTypeId())
			{
				case ParamType::BOOLEAN:
					$control = $params->addCheckbox($paramType->getName(), $paramType->getLabel());
					break;
				case ParamType::INTEGER:
					$control = $params->addText($paramType->getName(), $paramType->getLabel());
					$control->addRule(self::INTEGER);
					break;
				case ParamType::DOUBLE:
					$control = $params->addText($paramType->getName(), $paramType->getLabel());
					$control->addRule(self::FLOAT);
					break;
				case ParamType::STRING:
					$control = $params->addText($paramType->getName(), $paramType->getLabel());
					break;
				case ParamType::TEXT:
					$control = $params->addTextArea($paramType->getName(), $paramType->getLabel());
					break;
				case ParamType::DATETIME:
					$control = $params->addDatePicker($paramType->getName(), $paramType->getLabel());
					break;
			}
			
			$control->setOption('id', $paramType->getId());
			
			if ($paramType->isRequired())
			{
				$control->setRequired();
			}
		}
		
		$this->addSubmit('save', 'Ulož');
	}
}