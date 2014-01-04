<?php
namespace CertificatesModule\AdminModule;

use CertificatesModule\AdminModule\Components\CertificateTable,
	CertificatesModule\Components\CertificateViewControl,
	CertificatesModule\AdminModule\Forms\CertificateForm,
	CertificatesModule\AdminModule\Forms\CertificateTypeSelectForm,
	CertificatesModule\AdminModule\Forms\ImportCertificatesForm,
	CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	CertificatesModule\Models\Certificate\CertificateEntity,
	CertificatesModule\Models\ParamType\ParamType,
	CertificatesModule\Models\ParamType\ParamTypeEntity,
	DateTime,
	Nette\Forms\Controls\SubmitButton;

class CertificatePresenter extends \Presenters\BasePresenter
{
	/**
	 * @autowire(CertificatesModule\Models\CertificateType\CertificateTypeEntity,
	 * 	factory=Kdyby\Doctrine\EntityDaoFactory)
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $certificateTypeDao;
	/**
	 * @autowire(CertificatesModule\Models\Certificate\CertificateEntity,
	 * 	factory=Kdyby\Doctrine\EntityDaoFactory)
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $certificateDao;
	/**
	 * @var CertificateTypeEntity
	 */
	private $certificateTypeEntity = NULL;
	/**
	 * @var CertificateEntity
	 */
	private $certificateEntity = NULL;


	/**
	 * @var int $id
	 */
	public function actionDefault($id)
	{
		$this->certificateEntity = $this->certificateDao->find($id);

		if (!$this->certificateEntity)
		{
			throw new \Nette\Application\BadRequestException('Certificate not found.', 404);
		}

		$this->template->certificate = $this->certificateEntity;
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
		$code = $this->generateCode();

		$certificateEntity = new CertificateEntity($this->certificateTypeEntity, $code);
		$certificateEntity->setExpiration($values->expiration);

		foreach ($this->certificateTypeEntity->getParamTypes() as $paramType)
		{
			$paramName = $paramType->getName();
			$param = $this->createParamEntity($certificateEntity, $paramType, $values->params->$paramName);

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
	 * @param int $certificateTypeId
	 */
	public function actionImport($certificateTypeId = NULL)
	{
		if ($certificateTypeId !== NULL)
		{
			$this->certificateTypeEntity = $this->certificateTypeDao->find($certificateTypeId);
		}

		$this['importCertificatesForm']['certificateType']->setDefaultValue($this->certificateTypeEntity);
	}

	/**
	 * @param ImportCertificatesForm $form
	 */
	public function importCertificates(ImportCertificatesForm $form)
	{
		$this->certificateTypeEntity = $form->getValues()->certificateType;
		$xmlFile = $form->getValues()->file;

		$xml = new \SimpleXMLElement($xmlFile->getContents());

		foreach ($xml->certificateType->certificate as $certificateXml)
		{
			$code = $this->generateCode();
			$certificateEntity = new CertificateEntity($this->certificateTypeEntity, $code);

			$created = (string) $certificateXml->created;
			$created = empty($created) ? new DateTime()
				: DateTime::createFromFormat(DateTime::W3C, (string) $certificateXml->created);
			$certificateEntity->setCreated($created);

			$expiration = (string) $certificateXml->expiration;
			$expiration = empty($expiration) ? NULL
				: DateTime::createFromFormat(DateTime::W3C, (string) $certificateXml->expiration);
			$certificateEntity->setExpiration($expiration);

			foreach ($this->certificateTypeEntity->getParamTypes() as $paramType)
			/* @var $paramType ParamTypeEntity */
			{
				$paramName = $paramType->getName();
				$param = $this->createParamEntity($certificateEntity, $paramType,
					(string) $certificateXml->$paramName);

				$certificateEntity->getParams()->add($param);
			}

			$this->certificateDao->save($certificateEntity);
		}

		$this->certificateDao->getEntityManager()->flush();

		$this->flashMessage('Certifikáty boli úspešne importované.', 'success');
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

	/**
	 * @return ImportCertificatesForm
	 */
	protected function createComponentImportCertificatesForm()
	{
		$form = new ImportCertificatesForm($this->certificateTypeDao);
		$form->onSuccess[] = callback($this, 'importCertificates');

		return $form;
	}

	/**
	 * @return CertificateViewControl
	 */
	protected function createComponentCertificateView()
	{
		return new CertificateViewControl($this->certificateEntity);
	}

	/**
	 * @param CertificateEntity $certificateEntity
	 * @param ParamTypeEntity $paramTypeEntity
	 * @param mixed $value
	 * @return \CertificatesModule\Models\Param\ParamEntity
	 */
	private function createParamEntity(CertificateEntity $certificateEntity,
		ParamTypeEntity $paramTypeEntity, $value)
	{
		switch ($paramTypeEntity->getParamTypeId())
		{
			case ParamType::BOOLEAN:
				return new \CertificatesModule\Models\Param\BooleanParamEntity(
					$paramTypeEntity, $certificateEntity, (boolean) $value);
			case ParamType::INTEGER:
				return new \CertificatesModule\Models\Param\IntegerParamEntity(
					$paramTypeEntity, $certificateEntity, (int) $value);
			case ParamType::DOUBLE:
				return new \CertificatesModule\Models\Param\DoubleParamEntity(
					$paramTypeEntity, $certificateEntity, (double) $value);
			case ParamType::STRING:
				return new \CertificatesModule\Models\Param\StringParamEntity(
					$paramTypeEntity, $certificateEntity, (string) $value);
			case ParamType::TEXT:
				return new \CertificatesModule\Models\Param\TextParamEntity(
					$paramTypeEntity, $certificateEntity, (string) $value);
			case ParamType::DATETIME:
				$value = is_string($value) ?
					DateTime::createFromFormat(DateTime::W3C, $value) : $value;

				return new \CertificatesModule\Models\Param\DateTimeParamEntity(
					$paramTypeEntity, $certificateEntity, $value);
		}

		throw new \Nette\InvalidArgumentException('Undefined param type.');
	}

	/**
	 * @return string
	 */
	private function generateCode()
	{
		do
		{
			$code = \Nette\Utils\Strings::random(8);
		}
		while ($this->certificateDao->findOneBy(array('code' => $code)));

		return $code;
	}
}