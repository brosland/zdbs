<?php
namespace Brosland\Components\Table;

use Nette\Application\UI\Form;

class TableForm extends Form
{
	/** @var Table */
	private $table;
	
	
	/**
	 * @param Table
	 */
	public function __construct(Table $table)
	{
		parent::__construct();
		
		$this->table = $table;
		
		$this->getElementPrototype()
			->class('ajax');
		
		$this->addContainer('toolbar');
		
		$sorting = $this->addContainer('sorting');
		$sorting->addSelect('type');
		$sorting->addCheckbox('direction')
			->setAttribute('title', 'Zoradiť obrátene');
		$sorting->addSubmit('sort', 'Zoradiť')
			->onClick[] = callback($this, 'handleSort');
		
		$this->onSuccess[] = callback($this, 'handleForm');
	}
	
	/**
	 * @param \Nette\ComponentModel\IComponent
	 */
	public function attached($presenter)
	{
		if($presenter instanceof \Nette\Application\UI\Presenter)
		{
			if($this->table->isSelectable())
			{
				$this->addCheckbox('checkedAll')
					->setAttribute('title', 'Označiť všetko');

				$this->getRows();
			}
		}
		
		parent::attached($presenter);
	}
	
	/**
	 * @param string
	 * @param string
	 * @return \Nette\Forms\Controls\Button
	 */
	public function addToolbarButton($name, $caption = NULL)
	{
		return $this->getComponent('toolbar')->addSubmit($name, $caption);
	}
	
	/**
	 * @return \Nette\Forms\Container
	 */
	public function getToolbar()
	{
		return $this->getComponent('toolbar');
	}
	
	/**
	 * @return \Nette\Forms\Container
	 */
	public function getRows()
	{
		$rows = $this->getComponent('rows', FALSE);
		
		if($rows == NULL)
		{
			$rows = $this->addContainer('rows');
			
			for($i = 0; $i < $this->table->getItemsPerPage(); $i++)
			{
				$row = $rows->addContainer($i);
				$row->addHidden('id');
				$row->addCheckbox('checked');
			}
		}
		
		return $rows;
	}
	
	/**
	 * @param array
	 */
	public function setIds(array $ids)
	{
		$this->getRows()->setValues($ids);
	}
	
	/**
	 * @return array
	 */
	public function getSelectedItems()
	{
		$values = $this->getValues();
		$ids = array();
		
		foreach($values->rows as $row)
		{
			if(!empty($row->id))
			{
				if($values->checkedAll || $row->checked)
				{
					$ids[] = $row->id;
				}
			}
			else break;
		}
		
		return $this->table->getModel()->getItemsByIds($ids);
	}
	
	/**
	 * @param Form
	 */
	public function handleSort($button)
	{
		$sorting = $this->values->sorting;
		$this->table->sorting = array($sorting->type => $sorting->direction ? 'desc' : 'asc');
	}
	
	/**
	 * @param Form
	 */
	public function handleForm(Form $form)
	{		
		if($this->presenter->isAjax())
		{
			if($this->table->isSelectable())
			{
				$this['checkedAll']->setValue(FALSE);

				foreach($this['rows']->getComponents(TRUE, 'Nette\Forms\Controls\Checkbox') as $checkbox)
				{
					$checkbox->setValue(FALSE);
				}
			}
			
			$this->table->invalidateControl();
		}
		else
		{
			$this->table->redirect('this');
		}
	}
}