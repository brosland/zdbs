<?php
namespace Brosland\Components\Gallery;

use Brosland\Application\UI\Control,
	Brosland\Components\Uploader\Uploader,
	Doctrine\Common\Collections\Collection,
	Brosland\Media\Image,
	Brosland\Media\ImageDao,
	Nette\Http\FileUpload;

class Gallery extends Control
{
	/** @var ImageDao */
	private $images;
	/** @var Collection */
	private $gallery;
	/** @var string */
	public $addFilesButtonLabel = 'Pridať fotku';
    /** @var int */
    public static $imageMaxWidth = 1024;
    /** @var int */
    public static $imageMaxHeight = 768;
	
	
	/**
	 * @param ImageDao
	 * @param Collection
	 */
	public function __construct(ImageDao $images, Collection $gallery)
	{
		parent::__construct();
		
		$this->images = $images;
		$this->gallery = $gallery;
	}
	
	/**
	 * @return Collection
	 */
	public function getGallery()
	{
		return $this->gallery;
	}
	
	/**
	 * @param FileUpload
	 */
	public function addImage(FileUpload $fileUpload)
	{
		$this->gallery->add(new Image($fileUpload));
		$this->images->save($this->gallery);
	}
	
	/**
	 * @return Uploader
	 */
	public function createComponentUploader()
	{
		$fileTypes = array(
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
			'image/gif' => 'gif'
		);
		
		$uploader = new Uploader($fileTypes);
		$uploader->onSuccess[] = callback($this, 'addImage');
		
		return $uploader;
	}
	
	/**
	 * @return FileTable
	 */
	public function createComponentImageTable()
	{
		return new ImageTable($this->images, $this->gallery);
	}
	
	public function render()
	{
		$this->template->setFile(__DIR__ . '/templates/template.latte');
		$this->template->render();
	}
}