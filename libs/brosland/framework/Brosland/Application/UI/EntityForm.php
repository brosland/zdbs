<?php
namespace Brosland\Application\UI;

use Brosland\Model\IEntity,
	Doctrine\Common\Collections\Collection,
	Nette\Forms\Container;

abstract class EntityForm extends Form
{
	/**
	 * @var IEntity
	 */
	protected $entity = NULL;


	/**
	 * @return bool
	 */
	public function hasEntity()
	{
		return $this->entity !== NULL;
	}

	/**
	 * @return IEntity
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * @param IEntity $entity
	 */
	public function bindEntity(IEntity $entity)
	{
		$this->entity = $entity;
		$reflection = $entity->getReflection();

		foreach ($this->getComponents() as $name => $component)
		{
			if ($component instanceof Container)
			{
				continue;
			}
	
			$value = NULL;

			if ($reflection->hasMethod('get' . ucfirst($name)))
			{
				$value = $reflection->getMethod('get' . ucfirst($name))->invoke($entity);
			}
			else if ($reflection->hasMethod('is' . ucfirst($name)))
			{
				$value = $reflection->getMethod('is' . ucfirst($name))->invoke($entity);
			}
			else
			{
				continue;
			}

			if ($value instanceof IEntity)
			{
				$value = $value->getId();
			}
			else if ($value instanceof Collection)
			{
				$value = array_map(function(IEntity $entity) {
					return $entity->getId();
				}, $value->toArray());
			}

			$component->setDefaultValue($value);
		}
	}
}