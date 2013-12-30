<?php
namespace Brosland\Model;

use Doctrine\ORM\Event\LifecycleEventArgs,
	Kdyby\Doctrine\EntityDao,
	Kdyby\Events\Subscriber;

abstract class EventSubscriber extends \Nette\Object implements Subscriber
{
	/**
	 * @param LifecycleEventArgs $args
	 * @return EntityDao
	 */
	protected function getDao(LifecycleEventArgs $args)
	{
		return $args->getEntityManager()
			->getRepository($args->getEntity()->getReflection()->getName());
	}
	
	/**
	 * @return array
	 */
	public abstract function getSubscribedEvents();
}