<?php
namespace Brosland\Forms\Controls;

use Nette\Forms\Controls\MultiSelectBox;

class EntityMultiSelectBox extends MultiSelectBox
{
	/** @var array */
	private $entities;
	/** @var string */
	private $nameKey;
	
	
	/**
	 * @param string $label
	 * @param array $entities
	 * @param int $size
	 * @param string|callable $nameKey
	 */
	public function __construct($label = NULL, array $entities = array(), $size = NULL, $nameKey = 'name')
	{
		$this->nameKey = $nameKey;

		parent::__construct($label, NULL, $size);

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
	 * @param mixin $value
	 * @return self
	 */
	public function setValue($value)
	{
		parent::setValue($this->prepareItems($value));
		return $this;
	}
	
	/**
	 * @param mixin $value
	 * @return self
	 */
	public function setDefaultValue($value)
	{
		parent::setDefaultValue($this->prepareItems($value));
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getValue()
	{
		$back = debug_backtrace();
		
		if(isset($back[1]["function"]) && isset($back[1]["class"])
			&& $back[1]["function"] === "getControl"
				&& $back[1]["class"] === "Nette\Forms\Controls\SelectBox")
		{
			return parent::getValue();
		}
		
		$keys = parent::getValue();
		$values = array();
		
		foreach($keys as $key)
		{
			foreach($this->entities as $entity)
			{
				if($entity->getId() == $key)
				{
					$values[] = $entity;
				}
			}
		}
		
		return $values;
	}
	
	/**
	 * @param array $value
	 */
	private function prepareItems(array $value) // array ?
	{
		if($value === NULL)
		{
			return array();
		}
		
		$items = array();

		foreach($value as $item)
		{
			if(is_object($item))
			{
				$items[] = $item->getId();
			}
			else
			{
				$items[] = $item;
			}
		}
		
		return $items;
	}
}