<?php
namespace CertificatesModule\AdminModule;

use CertificatesModule\AdminModule\Components\CertificateTable,
	CertificatesModule\AdminModule\Forms\CertificateForm,
	CertificatesModule\AdminModule\Forms\CertificateTypeSelectForm,
	CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamType,
	Kdyby\Doctrine\EntityDao,
	Nette\Forms\Controls\SubmitButton;

class CertificatePresenter extends \Brosland\Application\UI\SecurityPresenter
{
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity = NULL;
	/**
	 * @var EntityDao
	 */
	private $certificateDao;


	public function startup()
	{
		parent::startup();

		$this->certificateTypeDao = $this->context->getService('certificates.certificateTypeDao');
		$this->certificateDao = $this->context->getService('certificates.certificateDao');
	}

	/**
	 * @param int $certificateTypeId
	 */
	public function actionAdd($certificateTypeId)
	{
		$this->certificateTypeEntity = $this->certificateTypeDao->find($certificateTypeId);

		if (!$this->certificateTypeEntity)
		{
			throw new \Nette\Application\BadRequestException('Certificate type not found.', 404);
		}

		$this->setView('edit');

		$certificateForm = $this['certificateForm'];
		$certificateForm['save']->onClick[] = callback($this, 'addCertificate');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function addCertificate(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();

		do
		{
			$code = \Nette\Utils\Strings::random(8);
		}
		while ($this->certificateDao->findOneBy(array('code' => $code)));

		$certificateEntity = new CertificateEntity($this->certificateTypeEntity, $code);
		$certificateEntity->setExpiration($values->expiration);

		foreach ($this->certificateTypeEntity->getParamTypes() as $paramType)
		{
			$param = NULL;
			$paramName = $paramType->getName();

			switch ($paramType->getParamTypeId())
			{
				case ParamType::BOOLEAN:
					$param = new \CertificatesModule\Models\Param\BooleanParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
					break;
				case ParamType::INTEGER:
					$param = new \CertificatesModule\Models\Param\IntegerParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
					break;
				case ParamType::DOUBLE:
					$param = new \CertificatesModule\Models\Param\DoubleParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
					break;
				case ParamType::STRING:
					$param = new \CertificatesModule\Models\Param\StringParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
					break;
				case ParamType::TEXT:
					$param = new \CertificatesModule\Models\Param\TextParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
					break;
				case ParamType::DATETIME:
					$param = new \CertificatesModule\Models\Param\DateTimeParamEntity(
						$paramType, $certificateEntity, $values->params->$paramName);
			}

			$certificateEntity->getParams()->add($param);
		}

		$this->certificateDao->save($certificateEntity);

		$this->flashMessage('Certifikát bol úspešne pridaný.', 'success');
		$this->redirect('list');
	}

	/**
	 * @var int $id
	 */
	public function actionEdit($id)
	{
		$certificateEntity = $this->certificateDao->find($id);
		
		if (!$certificateEntity)
		{
			throw new \Nette\Application\BadRequestException('Certificate not found.');
		}

		$this->certificateTypeEntity = $certificateEntity->getCertificateType();

		$certificateForm = $this['certificateForm'];
		$certificateForm->bindEntity($certificateEntity);
		$certificateForm['save']->onClick[] = callback($this, 'editCertificate');
	}

	/**
	 * @param SubmitButton $button
	 */
	public function editCertificate(SubmitButton $button)
	{
		$values = $button->getForm()->getValues();
		$certificateEntity = $button->getForm()->getEntity()
			->setExpiration($values->expiration);

		foreach ($certificateEntity->getParams() as $param)
		/* @var $param \CertificatesModule\Models\Param\ParamEntity */
		{
			$name = $param->getParamType()->getName();
			$param->setValue($values->params->$name);
		}
		
		$this->certificateDao->save($certificateEntity);
		$this->certificateDao->getEntityManager()->flush();

		$this->flashMessage('Certifikát bol úspešne upravený.', 'success');
		$this->redirect('list');
	}

	/**
	 * @return CertificateTypeSelectForm
	 */
	protected function createComponentCertificateTypeSelectForm()
	{
		$form = new CertificateTypeSelectForm($this->certificateTypeDao);
		$form->onSuccess[] = callback(function(CertificateTypeSelectForm $form) {
			$certificateType = $form->getValues()->certificateType;
			$form->getPresenter()->redirect('add', $certificateType->getId());
		});

		return $form;
	}

	/**
	 * @return CertificateForm
	 */
	protected function createComponentCertificateForm()
	{
		return new CertificateForm($this->certificateTypeEntity, $this->certificateDao);
	}

	/**
	 * @return CertificateTable
	 */
	protected function createComponentCertificateTable()
	{
		$queryBuilder = $this->certificateDao->createQueryBuilder('certificate')
			->leftJoin('certificate.certificateType', 'certificateType')
			->leftJoin('certificateType.category', 'category')
			->groupBy('certificate.id');

		return new CertificateTable($this->certificateDao, $queryBuilder);
	}
}