<?php
namespace Brosland\Forms\Controls;

use Brosland\Model\IEntity,
	Nette\Forms\Controls\SelectBox;

class EntitySelectBox extends SelectBox
{
	/** @var array */
	private $entities = array();
	/** @var string|callable */
	private $nameKey;
	
	
	/**
	 * @param string $label
	 * @param array $entities
	 * @param string|callable $nameKey
	 */
	public function __construct($label = NULL, array $entities = NULL, $nameKey = 'name')
	{
		$this->nameKey = $nameKey;

		parent::__construct($label);

		if($entities !== NULL)
		{
			$this->setItems($entities);
		}
	}

	/**
	 * @param array $entities
	 * @param bool $useKeys
	 * @return self
	 */
	public function setItems(array $entities, $useKeys = TRUE)
	{
		$this->entities = $entities;
		$items = array();
		
		foreach($entities as $entity)
		{
			if(is_callable($this->nameKey))
			{
				$label = $this->nameKey->invokeArgs(array($entity));
			}
			else
			{
				$label = callback($entity, 'get' . ucfirst($this->nameKey))->invoke();
			}
			
			$items[$entity->getId()] = $label;
		}
		
		parent::setItems($items);
		return $this;
	}
	
	/**
	 * @param IEntity|mixed $value
	 * @return self
	 */
	public function setValue($value)
	{
		if($value instanceof IEntity)
		{
			$value = $value->getId();
		}
		
		parent::setValue($value);
		return $this;
	}
	
	/**
	 * @param IEntity|mixed $value
	 * @return self
	 */
	public function setDefaultValue($value)
	{
		if($value instanceof IEntity)
		{
			$value = $value->getId();
		}
		
		parent::setDefaultValue($value);
		return $this;
	}
	
	/**
	 * @return IEntity
	 */
	public function getValue()
	{
		$back = debug_backtrace();
		
		if(isset($back[1]['function']) && isset($back[1]['class'])
			&& $back[1]['function'] === 'getControl'
				&& $back[1]['class'] === 'Nette\Forms\Controls\SelectBox')
		{
			return parent::getValue();
		}
		
		$val = parent::getValue();
		
		foreach($this->entities as $entity)
		{
			if($entity->getId() == $val)
			{
				return $entity;
			}
		}
		
		return NULL;
	}
}