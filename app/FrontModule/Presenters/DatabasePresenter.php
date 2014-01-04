<?php
namespace FrontModule;

class DatabasePresenter extends \Presenters\BasePresenter
{
	/**
	 * @autowire
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	protected $entityManager;


	public function actionUpdate()
	{
		$metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
		$this->entityManager->getProxyFactory()->generateProxyClasses($metadata);

		$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
		$schemaTool->updateSchema($metadata);

		echo 'Databáza bola úspešne aktualizovaná.';
		$this->terminate();
	}
}