<?php
namespace CertificatesModule\AdminModule\Forms;

use Brosland\Model\IEntity,
	CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\ParamType\ParamType,
	Nette\Application\IPresenter,
	Nette\Forms\Container;

class CertificateForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity;


	/**
	 * @param CertificateTypeEntity $certificateTypeEntity
	 */
	public function __construct(CertificateTypeEntity $certificateTypeEntity)
	{
		parent::__construct();

		$this->certificateTypeEntity = $certificateTypeEntity;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$this->addGroup('Certifikát');
		$this->addDatePicker('expiration', 'Dátum expirácie');

		$this->addGroup('Parametre certifikátu');
		$params = $this->addContainer('params');

		foreach ($this->certificateTypeEntity->getParamTypes() as $paramType)
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

			if ($paramType->isRequired())
			{
				$control->setRequired();
			}
		}

		$this->addSubmit('save', 'Ulož');
	}

	/**
	 * @param IEntity $entity
	 * @param Container $container
	 */
	public function bindEntity(IEntity $entity, Container $container = NULL)
	{
		parent::bindEntity($entity, $container);

		$params = $this->getComponent('params');

		foreach ($entity->getParams() as $param)
		{
			$params[$param->getParamType()->getName()]->setDefaultValue($param->getValue());
		}
	}
}