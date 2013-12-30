<?php
namespace Brosland\Components\Gallery;

use Brosland\Components\Table\Models\DoctrineCollectionModel,
	Brosland\Components\Table\Table,
	Brosland\Media\ImageDao,
	Doctrine\Common\Collections\Collection;

class ImageTable extends Table
{
	/** @var ImageDao */
	private $imageDao;
	/** @var Collection */
	private $collection;
	

	/**
	 * @param ImageDao $imageDao
	 * @param Collection $collection
	 */
	public function __construct(ImageDao $imageDao, Collection $collection)
	{
		$this->imageDao = $imageDao;
		$this->collection = $collection;
		
		parent::__construct(new DoctrineCollectionModel($this, $this->collection));
	}
	
	/**
	 * @param \Nette\Application\UI\Presenter $presenter
	 */
	protected function configure(\Nette\Application\UI\Presenter $presenter)
	{
		// columns
		$this->addColumn('thumbnail', 'Náhľad');
		$this->addColumn('name', 'Názov');
		$this->addColumn('size', 'Veľkosť');
		
		// actions
		$this->addAction('link', 'Link');
		
		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteImages');
		
		// css
		$this->setTableClass('file-table');
	}
	
	public function deleteImages($button)
	{
		$images = $button->form->getSelectedItems();
		
		if(!empty($images))
		{
			$this->imageDao->delete($images);
			$this->flashMessage('Fotky boli zmazané.', 'success');
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