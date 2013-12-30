<?php
namespace Brosland\Components\Table\Models;

use Brosland\Components\Table\Table,
	Doctrine\Common\Collections\Collection;

class DoctrineCollectionModel implements IModel
{
	/** @var Table */
	private $table;
	/** @var Collection */
	private $collection;
	
	
	/**
	 * @param Table
	 * @param QueryBuilder
	 */
	public function __construct(Table $table, Collection $collection)
	{
		$this->table = $table;
		$this->collection = $collection;
	}
	
	/**
	 * @return Table
	 */
	public function getTable()
	{
		return $this->table;
	}
	
	/**
	 * @param object
	 * @return int|string
	 */
	public function getItemId($item)
	{
		return $item->id;
	}
	
	/**
	 * @param object
	 * @param string
	 * @return mixed
	 */
	public function getItemProperty($item, $property)
	{
		$value = $item;
		
		foreach(explode('.', $property) as $field)
		{
			$value = $value->$field;
		}
		
		return $value;
	}
	
	/**
	 * @param int
	 */
	public function getItem($id)
	{
		foreach($this->collection as $item)
		{
			if($item->id == $id)
			{
				return $item;
			}
		}
		
		return NULL;
	}
	
	/**
	 * @return object[]
	 */
	public function getItems()
	{
		$paginator = $this->table->getPaginator();
		$paginator->setItemCount($this->collection->count());

		return array_values($this->collection->slice($paginator->offset, $paginator->itemsPerPage));
	}
	
	/**
	 * @return object[]
	 */
	public function getItemsByIds(array $ids = array())
	{
		return $this->collection->filter(function($item) use ($ids){
			return in_array($item->id, $ids) ? $item : NULL;
		})->toArray();
	}
}