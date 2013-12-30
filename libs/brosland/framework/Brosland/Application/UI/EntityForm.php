<?php
namespace Brosland\Application\UI;

use Brosland\Model\IEntity,
	Doctrine\Common\Collections\Collection,
	Nette\Forms\Container;

abstract class EntityForm extends Form
{
	/** @var IEntity */
	protected $entity = NULL;
	
	
	/**
	 * @return IEntity
	 */
	public function getEntity()
	{
		return $this->entity;
	}
	
	/**
	 * @param IEntity $entity
	 * @param Container|NULL $container
	 */
	public function bindEntity(IEntity $entity, Container $container = NULL)
	{
		$this->entity = $entity;
		$reflection = $entity->getReflection();
		
		$components = $container == NULL ? $this->getComponents() : $container->getComponents();
		
		foreach($components as $name => $component)
		{
			$value = NULL;
			
			if($reflection->hasMethod('get' . ucfirst($name)))
			{
				$value = $reflection->getMethod('get' . ucfirst($name))->invoke($entity);
			}
			else if($reflection->hasMethod('is' . ucfirst($name)))
			{
				$value = $reflection->getMethod('is' . ucfirst($name))->invoke($entity);
			}
			else
			{
				continue;
			}
			
			if($component instanceof Container)
			{
				$this->bindEntity($value, $component);
				continue;
			}
			
			if($value instanceof IEntity)
			{
				$value = $value->getId();
			}
			else if($value instanceof Collection)
			{
				$value = array_map(function(IEntity $entity) {
					return $entity->getId();
				}, $value->toArray());
			}

			$component->setDefaultValue($value);
		}
	}
}