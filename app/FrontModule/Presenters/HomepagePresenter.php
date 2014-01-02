<?php
namespace FrontModule;

use CertificatesModule\Models\Certificate\CertificateEntity,
	Kdyby\Doctrine\EntityDao,
	FrontModule\Forms\FindForm;

class HomepagePresenter extends BasePresenter
{
	/**
	 * @var EntityDao
	 */
	private $certificateDao;
	/**
	 * @var CertificateEntity
	 */
	private $certificateEntity = NULL;
	
	
	public function startup()
	{
		parent::startup();

		$this->certificateDao = $this->context->getService('certificates.certificateDao');
	}

	public function actionDefault()
	{
		$this->setView('find');

		$findForm = $this['findForm'];
		$findForm['save']->onClick[] = callback($this, 'findCertificate');
	}
	
	public function findCertificate()
	{		
		$value = $this['findForm']['find']->getValue();
		$prefix = substr($value, 0, strlen($value)-8);
		$code = substr($value, strlen($prefix));
		
		$this->certificateEntity = $this->certificateDao->findOneBy(array('code' => $code));

		if (! $this->certificateEntity || $this->certificateEntity->getFullCode() !== $value)
		{
			$this->flashMessage('Certifikát nenájdený', 'Certifikát nenájdený');
		}
		else
		{
			$this->flashMessage('Certifikát nájdený', 'Certifikát nájdený');
		}
	}
	
	/**
	 * @return CategoryForm
	 */
	protected function createComponentFindForm()
	{
		return new FindForm($this->certificateDao, $this->certificateEntity);
	}
}