<?php
namespace Brosland\Components\Table\Models;

interface IModel
{
	/**
	 * @return \Components\Table\Table
	 */
	public function getTable();
	
	/**
	 * @param object
	 * @return mixed
	 */
	public function getItemId($item);
	
	/**
	 * @param object
	 * @param string
	 * @return mixed
	 */
	public function getItemProperty($item, $property);
	
	/**
	 * @param mixed
	 * @return object
	 */
	public function getItem($id);
	
	/**
	 * @return object[]
	 */
	public function getItems();
	
	/**
	 * @param array
	 * @return object[]
	 */
	public function getItemsByIds(array $ids);
}