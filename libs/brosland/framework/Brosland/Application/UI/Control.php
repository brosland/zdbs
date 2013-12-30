<?php
namespace Brosland\Application\UI;

use Nette\Localization\ITranslator;

abstract class Control extends \Nette\Application\UI\Control
{
	/** @var ITranslator */
	protected $translator;
	
	
	/**
	 * @param string|array snippet names
	 * @param string link destination in format "[[module:]presenter:]view" or "signal!"
	 * @param array|mixed
	 * @return void
	 */
	public function refresh($snippets = NULL, $destination = 'this', $args = array())
	{
		if($this->presenter->isAjax())
		{
			if($snippets)
			{
				foreach((array) $snippets as $snippet)
				{
					$this->invalidateControl($snippet);
				}
			}
			else
			{
				$this->invalidateControl();
			}
		}
		else if($destination)
		{
			$this->redirect($destination, $args);
		}
	}
	
	/**
	 * @param ITranslator $translator
	 */
	public function setTranslator(ITranslator $translator = NULL)
	{
		$this->translator = $translator;
	}
}