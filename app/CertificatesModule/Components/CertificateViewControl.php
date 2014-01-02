<?php
namespace CertificatesModule\Components;

use CertificatesModule\Models\Certificate\CertificateEntity;

class CertificateViewControl extends \Brosland\Application\UI\Control
{
	/**
	 * @var CertificateEntity
	 */
	private $certificateEntity;


	/**
	 * @param CertificateEntity $certificateEntity
	 */
	public function __construct(CertificateEntity $certificateEntity)
	{
		parent::__construct();

		$this->certificateEntity = $certificateEntity;
	}
	
	/**
	 * @param  string|NULL
	 * @return Nette\Templating\ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = new \Nette\Templating\Template();
		$template->registerFilter(new \Nette\Latte\Engine());
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');

		return $template;
	}

	public function render()
	{
		$this->template->setSource($this->certificateEntity->getCertificateType()->getTemplate());

		foreach ($this->certificateEntity->getParams() as $param)
		/* @var $param \CertificatesModule\Models\Param\ParamEntity */
		{
			$paramName = $param->getParamType()->getName();
			$this->template->$paramName = $param->getValue();
		}
		
		$this->template->render();
	}
}