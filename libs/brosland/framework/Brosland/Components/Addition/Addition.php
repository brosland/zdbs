<?php
namespace Brosland\Components\Addition;

use Brosland\Application\UI\Control,
	Brosland\Components\Uploader\Uploader,
	Brosland\Media\File,
	Brosland\Media\FileDao,
	Doctrine\Common\Collections\Collection,
	Nette\Http\FileUpload;

class Addition extends Control
{
	/** @var string */
	private $title;
	/** @var FileDao */
	private $files;
	/** @var Collection */
	private $addition;
	/** @var string */
	public $addFilesButtonLabel = 'Pridať prílohu';
	
	
	/**
	 * @param FileDao
	 * @param Collection
	 */
	public function __construct(FileDao $files, Collection $addition)
	{
		parent::__construct();
		
		$this->files = $files;
		$this->addition = $addition;
	}
	
	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * @param string
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @return Collection
	 */
	public function getAddition()
	{
		return $this->addition;
	}
	
	/**
	 * @param FileUpload
	 */
	public function addFile(FileUpload $fileUpload)
	{
		$file = new File($fileUpload);
		$this->addition->add($file);
		$this->files->save($this->addition);
	}
	
	/**
	 * @return Uploader
	 */
	public function createComponentUploader()
	{
		$uploader = new Uploader(array());
		$uploader->onSuccess[] = callback($this, 'addFile');
		
		return $uploader;
	}
	
	/**
	 * @return FileTable
	 */
	public function createComponentFileTable()
	{
		return new FileTable($this->files, $this->addition);
	}
	
	public function render()
	{
		$this->template->setFile(__DIR__ . '/templates/template.latte');
		$this->template->render();
	}
}