<?php
namespace Brosland\Components\Table;

use	Nette\Application\UI\Control,
	Nette\Utils\Html;

class Button extends Control
{
	/** @var string */
	private $caption;
	/** @var string|callable */
	private $link = '#';
	/** @var array */
	private $attributes = array();
	/** @var string */
	private $icon;
	/** @var string|callable|NULL */
	private $confirm = NULL;
	/** @var callable|NULL */
	private $condition;
	
	
	/**
	 * @param string
	 */
	public function __construct($caption)
	{
		parent::__construct();
		
		$this->caption = $caption;
	}
	
	/**
	 * @return \Components\Table\Table
	 */
	public function getTable()
	{
		return $this->lookup('Brosland\Components\Table\Table');
	}
	
	/**
	 * @return string
	 */
	public function getLink()
	{
		if(is_callable($this->link))
		{
			return $this->link->invoke($this->table->getCurrentItem());
		}
		
		return $this->link;
	}
	
	/**
	 * @param string|callable
	 * @return Button
	 */
	public function setLink($link)
	{
		$this->link = $link;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getConfirm()
	{
		if(is_callable($this->confirm))
		{
			return $this->confirm->invoke($this->table->getCurrentItem());
		}
		
		return $this->confirm;
	}
	
	/**
	 * @param string|callable
	 * @return Button
	 */
	public function setConfirm($confirm)
	{
		$this->confirm = $confirm;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isShow()
	{
		if($this->condition !== NULL)
		{
			return $this->condition->invoke($this->table->getCurrentItem());
		}
		
		return TRUE;
	}
	
	/**
	 * @param callable
	 */
	public function setCondition($condition)
	{
		$this->condition = $condition;
	}
	
	/**
	 * @param string
	 * @param mixed
	 * @return Button
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
		return $this;
	}
	
	/**
	 * @param string
	 * @return Button
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;
		return $this;
	}
	
	public function render()
	{
		echo Html::el('a')
			->href($this->getLink())
			->class('ui-state-default')
			->addAttributes($this->attributes)
			->data('confirm', $this->getConfirm())
			->add(isset($this->icon) ? Html::el('span')->class('ui-icon ' . $this->icon) : $this->caption)
			->title(isset($this->icon) ? $this->caption : NULL);
	}
}