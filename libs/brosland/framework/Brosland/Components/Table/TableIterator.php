<?php
namespace Brosland\Components\Table;

class TableIterator extends \IteratorIterator
{
	/** @var Table */
	private $table;
	/** @var object[] */
	private $items;
	
	
	/**
	 * @param Table
	 */
	public function __construct(Table $table)
	{
		$model = $table->getModel();
		
		$this->table = $table;
		$this->items = $model->getItems();
		
		$table->getForm()->setIds(array_map(function($item) use($model) {
			return array('id' => $model->getItemId($item));
		}, $this->items));
		
		parent::__construct(new \ArrayIterator($this->items));
	}
	
	/**
	 * @return object[]
	 */
	public function getItems()
	{
		return $this->items;
	}
	
	/**
	 * @return int
	 */
	public function getTotalCount()
	{
		return $this->table->getPaginator()->getItemCount();
	}
	
	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->table->bindItem($this->key(), parent::current());
	}
}