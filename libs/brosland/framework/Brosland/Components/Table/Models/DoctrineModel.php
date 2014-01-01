<?php
namespace Brosland\Components\Table\Models;

use Brosland\Components\Table\Table,
	Doctrine\ORM\QueryBuilder,
	Doctrine\ORM\Tools\Pagination\Paginator,
	Nette\Callback;

class DoctrineModel implements IModel
{
	/** @var Table */
	private $table;
	/** @var QueryBuilder */
	private $queryBuilder;
	/** @var Callback */
	private $_getItems = NULL;
	
	
	/**
	 * @param Table
	 * @param QueryBuilder
	 */
	public function __construct(Table $table, QueryBuilder $queryBuilder)
	{
		$this->table = $table;
		$this->queryBuilder = $queryBuilder;
	}
	
	/**
	 * @return QueryBuilder
	 */
	public function getQueryBuilder()
	{
		return $this->queryBuilder;
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
		return $item->{$this->getPrimaryKey()};
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
			$value = $value->{'get' . ucfirst($field)}();
		}
		
		return $value;
	}
	
	/**
	 * @param int
	 */
	public function getItem($id)
	{
		$query = clone $this->queryBuilder;
		
		// filter
		$this->filterQuery($query);
		
		return $query->andWhere($query->expr()->eq($query->getRootAlias() . '.' . $this->getPrimaryKey(), $id))
			->getQuery()->getSingleResult();
	}
	
	/**
	 * @return object[]
	 */
	public function getItems()
	{
		$query = clone $this->queryBuilder;
		
		// filter
		$this->filterQuery($query);
		
		// count pages
		$paginator = new Paginator($query);
		$this->table->getPaginator()->setItemCount($paginator->count());
		
		// limit & offset
		$query->setMaxResults($this->table->getPaginator()->getLength());
		$query->setFirstResult($this->table->getPaginator()->getOffset());

		// sorting
		$this->sortQuery($query);
		
		// read items
		$items = $query->getQuery()->execute();
		
		if(is_callable($this->_getItems))
		{
			$items = $this->_getItems->invokeArgs(array($items));
		}
		
		return $items;
	}
	
	public function getItemsByIds(array $ids)
	{
		if(empty($ids))
		{
			return array();
		}
		
		$query = clone $this->queryBuilder;
		
		// filter
		$this->filterQuery($query);
		
		$query->andWhere($query->expr()->in($query->getRootAlias() . '.' . $this->getPrimaryKey(), $ids));
		
		return $query->getQuery()->execute();
	}
	
	/**
	 * @param QueryBuilder
	 */
	protected function sortQuery(QueryBuilder $query)
	{
		$sorting = $this->table->sorting;
		
		if(!empty($sorting))
		{
			$query->resetDQLPart('orderBy');
			
			foreach($sorting as $property => $direction)
			{
				$class = $this->getPropertyParentClass($property);
				$fields = array_slice(explode('.', $property), -2);

				if($class->isCollectionValuedAssociation($field = end($fields)))
				{
					$query->addSelect("COUNT({$field}) {$field}Count")
						->addOrderBy("{$field}Count", $direction);

					$this->_getItems = callback(function(array $results) {
						return array_map(function($result) { return $result[0]; }, $results);
					});
				}
				else
				{
					$field = count($fields) > 1 ? $fields[0] . '.' . $fields[1] : $query->getRootAlias() . '.' . $fields[0];
					$query->addOrderBy($field, $direction);
				}
			}
		}
	}
	
	/**
	 * @param QueryBuilder
	 */
	protected function filterQuery(QueryBuilder $query)
	{
	}
	
	/**
	 * @retrun string
	 */
	private function getPrimaryKey()
	{
		$ids = $this->getRootEntityClass()->getIdentifierFieldNames();
		return reset($ids);
	}

	/**
	 * @return \Doctrine\Mapping\ClassMetadata
	 */
	protected function getRootEntityClass()
	{
		$classes = $this->queryBuilder->getRootEntities();
		return $this->queryBuilder->getEntityManager()->getClassMetadata(reset($classes));
	}
	
	/**
	 * @param string
	 * @return \Doctrine\Mapping\ClassMetadata
	 */
	protected function getPropertyParentClass($property)
	{
		$class = $this->getRootEntityClass();
		$doctrine = $this->queryBuilder->getEntityManager();
		$fields = array_slice(explode('.', $property), 0, -1);
		
		foreach($fields as $field)
		{
			if($class->hasAssociation($field))
			{
				$class = $doctrine->getClassMetadata($class->getAssociationTargetClass($field));
			}
			else if($class->hasField($field))
			{
				return $class;
			}
		}
		
		return $class;
	}
}