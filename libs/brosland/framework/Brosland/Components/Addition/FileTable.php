<?php
namespace Brosland\Components\Addition;

use Brosland\Components\Table\Models\DoctrineCollectionModel,
	Brosland\Components\Table\Table,
	Brosland\Media\FileDao,
	Doctrine\Common\Collections\Collection;

class FileTable extends Table
{
	/** @var FileDao */
	private $fileDao;
	/** @var Collection */
	private $collection;
	

	/**
	 * @param FileDao
	 * @param Collection
	 */
	public function __construct(FileDao $fileDao, Collection $collection)
	{
		$this->fileDao = $fileDao;
		$this->collection = $collection;
		
		parent::__construct(new DoctrineCollectionModel($this, $this->collection));
	}
	
	/**
	 * @param \Nette\Application\UI\Presenter $presenter
	 */
	protected function configure(\Nette\Application\UI\Presenter $presenter)
	{
		// columns
		$this->addColumn('name', 'Názov');
		$this->addColumn('size', 'Veľkosť');
		
		// actions
		$this->addAction('link', 'Link');
		
		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteFiles');
		
		// css
		$this->setTableClass('file-table');
	}
	
	public function deleteFiles($button)
	{
		$files = $button->form->getSelectedItems();
		
		if(!empty($files))
		{
			$this->fileDao->delete($files);
			$this->flashMessage('Súbory boli zmazané.', 'success');
		}
	}
	
	/**
	 * @param string
	 * @return \Nette\Templating\FileTemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate();
		
		$parentTemplate = $template->getFile();
		$template->setFile(__DIR__ . '/templates/table.latte');
		$template->parentTemplate = $parentTemplate;
		
		return $template;
	}
}