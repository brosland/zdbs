<?php
namespace Brosland\Components\Table;

use Nette\Application\UI\Control,
	Nette\Utils\Html,
	Nette\Utils\Strings;

class Column extends Control
{
	/** @var string */
	private $label;
	/** @var string */
	private $property = NULL;
	/** @var callable */
	private $renderer = NULL;
	/** @var string */
	private $class;
	/** @var string */
	public $dateFormat = 'd.m.Y';
	/** @var int */
	public $maxLength = 0;
	
	
	/**
	 * @return Table
	 */
	public function getTable()
	{
		return $this->lookup('Brosland\Components\Table\Table');
	}
	
	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}
	
	/**
	 * @param string
	 * @return Column
	 */
	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getProperty()
	{
		return $this->property ?: $this->name;
	}
	
	/**
	 * @param string
	 * @return Column
	 */
	public function setProperty($property)
	{
		$this->property = $property;
		return $this;
	}
	
	/**
	 * @param callable
	 * @return Column
	 */
	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
		return $this;
	}
	
	/**
	 * @param string
	 * @return Column
	 */
	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getValue()
	{
		if(is_callable($this->renderer))
		{
			return $this->renderer->invoke($this->getTable()->getCurrentItem());
		}
		
		$value = $this->getTable()->getCurrentItemProperty($this->getProperty());
		
		if(is_bool($value))
		{
			return Html::el('span')->class('ui-icon ' . ($value ? 'ui-icon-check' : 'ui-icon-close'));
		}
		else if($value instanceof \DateTime)
		{
			return $value->format($this->dateFormat);
		}
		else if($value instanceof \Countable)
		{
			return $value->count();
		}
		else if($value === NULL)
		{
			return '';
		}
		else
		{
			return $this->maxLength > 0 ? Strings::truncate($value, $this->maxLength) : $value;
		}
	}
	
	public function renderHead()
	{
		echo Html::el('th')
			->setText($this->label);
	}
	
	public function renderCell()
	{
		echo Html::el('td')
			->add($this->getValue())
			->class($this->class);
	}
}