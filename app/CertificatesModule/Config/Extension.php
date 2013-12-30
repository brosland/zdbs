<?php
namespace CertificatesModule\Config;

class Extension extends \Nette\Config\CompilerExtension
{
	public function loadConfiguration()
	{
		parent::loadConfiguration();
		
		$builder = $this->getContainerBuilder();
				
		$builder->addDefinition('certificates.categoryDao')
			->setFactory('@doctrine.dao', array('CertificatesModule\Models\Category\CategoryEntity'));
		
		$builder->addDefinition('certificates.certificateTypeDao')
			->setFactory('@doctrine.dao', array('CertificatesModule\Models\CertificateType\CertificateTypeEntity'));
		
		$builder->addDefinition('certificates.certificateDao')
			->setFactory('@doctrine.dao', array('CertificatesModule\Models\Certificate\CertificateEntity'));
		
		$builder->addDefinition('certificates.paramTypeDao')
			->setFactory('@doctrine.dao', array('CertificatesModule\Models\ParamType\ParamTypeEntity'));
		
		$builder->addDefinition('certificates.paramDao')
			->setFactory('@doctrine.dao', array('CertificatesModule\Models\Param\ParamEntity'));
	}
}