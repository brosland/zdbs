<?php
namespace FrontModule;

use Kdyby\Doctrine\EntityManager;

class DatabasePresenter extends BasePresenter
{
	/** @var EntityManager */
	private $entityManager;


	/**
	 * @param EntityManager $entityManager
	 */
	public function injectEntityManager(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

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