<?php
namespace Brosland\Components\Table;

use Nette\Application\UI\Control,
	Nette\Application\IPresenter,
	Nette\ComponentModel\Container,
	Nette\Utils\Paginator;

class Table extends Control implements \IteratorAggregate
{
	// <editor-fold defaultstate="collapsed" desc="variables">
	
	/** @var IModel */
	private $model;
	/** @var TableForm */
	private $form;
	/** @var array */
	private $filter = array();
	/** @var bool */
	private $selectable = TRUE;
	/** @var ITableIterator */
	private $iterator = NULL;
	/** @var object */
	private $item = NULL;
	/** @var int */
	private $index = 0;
	/** @var Paginator */
	private $paginator;
	/** @var bool */
	private $sortable = TRUE;
	/**
	 * @array
	 * @persistent
	 */
	public $sorting = array();
	/**
	 * @var int
	 * @persistent
	 */
	public $page = 1;
	/** 
	 * @var int
	 * @persistent
	 */
	public $itemsPerPage = 10;
	/** @var string */
	private $tableClass;
	/** @var string|callback */
	private $rowClass;	
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="construct & configuration">
	
	/**
	 * @param Models\IModel
	 */
	public function __construct(Models\IModel $model)
	{
		parent::__construct();
		
		$this->model = $model;
		$this->paginator = new Paginator();
		$this->paginator->setItemsPerPage((int)$this->itemsPerPage);
	}
	
	/**
	 * @param \Nette\ComponentModel\Container
	 */
	protected function attached($container)
	{
		if(!$container instanceof IPresenter)
		{
			parent::attached($container);
			return;
		}
		
		// components
		$this->form = new TableForm($this);
		$this['columns'] = new Container();
		$this['actions'] = new Container();
		
		// load state
		parent::attached($container);
		$this->getPaginator()->setPage($this->page);
		
		// configure
		$this->configure($this->getPresenter());
		
		// sorting
		if($this->sortable && !empty($this->sorting))
		{
			$types = $this->getSortTypes();
			
			if(isset($types[key($this->sorting)]) && in_array(reset($this->sorting), array('asc', 'desc')))
			{
				$this->form['sorting']->setDefaults(array(
					'type' => key($this->sorting),
					'direction' => reset($this->sorting) == 'desc'
				));
			}
			else $this->sorting = array();
		}
		else
		{
			$this->sorting = array();
		}
	}
	
	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="columns">
	
	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return Column
	 */
	public function addColumn($name, $label = NULL, $property = NULL)
	{
		$this['columns']->addComponent(new Column(), $name);
		
		return $this['columns']->getComponent($name)
			->setLabel($label)
			->setProperty($property);
	}
	
	/**
	 * @param string
	 * @return bool
	 */
	public function hasColumn($name)
	{
		return $this->getColumn($name) !== NULL;
	}
	
	/**
	 * @param Column|NULL
	 */
	public function getColumn($name)
	{
		return $this['columns']->getComponent($name, FALSE);
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="buttons">
	
	/**
	 * @param string
	 * @param string
	 * @return Button
	 */
	public function addAction($name, $caption = NULL)
	{
		$this['actions']->addComponent(new Button($caption), $name);
		return $this['actions']->getComponent($name);
	}
	
	/**
	 * @return bool
	 */
	public function hasActions()
	{
		return count($this['actions']->getComponents()) > 0;
	}
	
	/**
	 * @param string
	 * @param string
	 * @return \Nette\Forms\Controls\Button
	 */
	public function addToolbarButton($name, $caption = NULL)
	{
		return $this->form->addToolbarButton($name, $caption);
	}
	
	/**
	 * @return bool
	 */
	public function hasToolbarButtons()
	{
		return count($this->form['toolbar']->getComponents()) > 0;
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="sorting">
	
	/**
	 * @return bool
	 */
	public function isSortable()
	{
		return $this->sortable;
	}
	
	/**
	 * @param bool
	 */
	public function setSortable($sortable)
	{
		$this->sortable = $sortable;
	}
	
	/**
	 * @return bool
	 */
	public function hasSorting()
	{
		return count($this->getSortTypes()) > 0;
	}
	
	/**
	 * @return array
	 */
	public function getSortTypes()
	{
		return $this->form['sorting']['type']->getItems();
	}
	
	/**
	 * @param array
	 */
	public function setSortTypes(array $types)
	{
		$this->form['sorting']['type']->setItems($types);
	}
	
	/**
	 * @param array
	 */
	public function setDefaultSorting(array $sorting)
	{
		if(empty($this->sorting))
		{
			$this->sorting = $sorting;
		}
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="iterator">
	
	/**
	 * @return TableIterator
	 */
	public function getIterator()
	{
		if($this->iterator == NULL)
		{
			$this->iterator = new TableIterator($this);
		}
		
		return $this->iterator;
	}
	
	/**
	 * @return int
	 */
	public function getIndex()
	{
		return $this->index;
	}
	
	/**
	 * @return int
	 */
	public function count()
	{
		return $this->getIterator()->getTotalCount();
	}
	
	/**
	 * @param int
	 * @param object
	 * @return object
	 */
	public function bindItem($index, $item)
	{
		$this->index = (int)$index;
		return $this->item = $item;
	}
	
	/**
	 * @return object
	 */
	public function getCurrentItem()
	{
		return $this->item;
	}
	
	/**
	 * @return mixed
	 */
	public function getCurrentItemId()
	{
		return $this->model->getItemId($this->getCurrentItem());
	}
	
	/**
	 * @param string $property
	 * @return mixed
	 */
	public function getCurrentItemProperty($property)
	{
		return $this->model->getItemProperty($this->getCurrentItem(), $property);
	}
	
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="paginator">
	
	/**
	 * @return Paginator
	 */
	public function getPaginator()
	{
		return $this->paginator;
	}
	
	/**
	 * @return int
	 */
	public function getItemsPerPage()
	{
		return $this->paginator->getItemsPerPage();
	}
	
	/**
	 * @param int
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		return $this->paginator->setItemsPerPage($itemsPerPage);
	}
	
	/**
	 * @param int
	 */
	public function handleSetPage($page)
	{
		if($this->presenter->isAjax())
		{
			$this->invalidateControl();
		}
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="filter">
	
	/**
	 * @return array
	 */
	public function getFilter()
	{
		return $this->filter;
	}
	
	/**
	 * @param array
	 */
	public function setFilter(array $filter)
	{
		$this->filter = $filter;
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="selecting">
	
	/**
	 * @return bool
	 */
	public function isSelectable()
	{
		return $this->selectable;
	}
	
	/**
	 * @param bool
	 */
	public function setSelectable($selectable)
	{
		$this->selectable = $selectable;
	}
	
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="css">
	
	/**
	 * @return string
	 */
	public function getTableClass()
	{
		return $this->tableClass;
	}
	
	/**
	 * @param string
	 */
	public function setTableClass($class)
	{
		$this->tableClass = $class;
	}
	
	/**
	 * @return string
	 */
	public function getRowClass()
	{
		return is_callable($this->rowClass) ? $this->rowClass->invoke($this) : $this->rowClass;
	}
	
	/**
	 * @param string|callback
	 */
	public function setRowClass($class)
	{
		$this->rowClass = $class;
	}
	
	// </editor-fold>
	
	/**
	 * @return Models\IModel
	 */
	public function getModel()
	{
		return $this->model;
	}
	
	/**
	 * @return TableModel
	 */
	public function getForm()
	{
		return $this->form;
	}
	
	public function createComponentForm()
	{
		return $this->form;
	}
	
	/**
	 * @param string
	 * @return \Nette\Templating\FileTemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate();
		$template->setFile(__DIR__ . '/templates/table.latte');
		
		return $template;
	}
	
	public function render()
	{		
		// init iterator
		$this->getIterator();
		
		$this->template->render();
	}
}