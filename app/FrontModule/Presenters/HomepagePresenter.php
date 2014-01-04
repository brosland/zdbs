<?php
namespace FrontModule;

use CertificatesModule\Components\CertificateViewControl,
	CertificatesModule\Models\Certificate\CertificateEntity,
	FrontModule\Forms\FindForm;

class HomepagePresenter extends \Presenters\BasePresenter
{
	/**
	 * @autowire(CertificatesModule\Models\Certificate\CertificateEntity,
	 * 	factory=Kdyby\Doctrine\EntityDaoFactory)
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $certificateDao;
	/**
	 * @var CertificateEntity
	 */
	private $certificateEntity = NULL;


	public function actionDefault()
	{
		$findForm = $this['findForm'];
		$findForm['save']->onClick[] = callback($this, 'findCertificate');
	}

	public function findCertificate()
	{
		$value = $this['findForm']['find']->getValue();
		$prefix = substr($value, 0, strlen($value) - 8);
		$code = substr($value, strlen($prefix));

		$this->certificateEntity = $this->certificateDao->findOneBy(array('code' => $code));

		if (!$this->certificateEntity || $this->certificateEntity->getFullCode() !== $value)
		{
			$this->flashMessage('Certifikát nenájdený', 'Certifikát nenájdený');
			return;
		}

		$this->template->certificate = $this->certificateEntity;
	}

	/**
	 * @return FindForm
	 */
	protected function createComponentFindForm()
	{
		return new FindForm($this->certificateDao);
	}

	/**
	 * @return CertificateViewControl
	 */
	protected function createComponentCertificateView()
	{
		return new CertificateViewControl($this->certificateEntity);
	}
}