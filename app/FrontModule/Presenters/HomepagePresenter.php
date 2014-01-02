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
	
	
	public function startup()
	{
		parent::startup();

		$this->certificateDao = $this->context->getService('certificates.certificateDao');
	}

	public function actionDefault()
	{
		$findForm = $this['findForm'];
		$findForm['save']->onClick[] = callback($this, 'findCertificate');
	}
	
	public function findCertificate()
	{		
		$value = $this['findForm']['find']->getValue();
		$prefix = substr($value, 0, strlen($value)-8);
		$code = substr($value, strlen($prefix));
		
		$certificateEntity = $this->certificateDao->findOneBy(array('code' => $code));

		if (!$certificateEntity || $certificateEntity->getFullCode() !== $value)
		{
			$this->flashMessage('Certifikát nenájdený', 'Certifikát nenájdený');
			return;
		}
		
		$template = new \Nette\Templating\Template();
		$template->registerFilter(new \Nette\Latte\Engine());
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->setSource($certificateEntity->getCertificateType()->getTemplate());
		
		foreach ($certificateEntity->getParams() as $param)
		/* @var $param \CertificatesModule\Models\Param\ParamEntity */
		{
			$paramName = $param->getParamType()->getName();
			$template->$paramName = $param->getValue();
		}
		
		$this->template->certificate = $certificateEntity;
		$this->template->certificateTemplate = $template;
	}
	
	/**
	 * @return CategoryForm
	 */
	protected function createComponentFindForm()
	{
		return new FindForm($this->certificateDao);
	}
}