<?php
namespace Brosland\Application\UI;

abstract class Presenter extends \Nette\Application\UI\Presenter
{
	/** @var string */
	protected $moduleName;
	/** @var string */
	protected $presenterName;
	/** @var \Nette\Localization\ITranslator */
	protected $translator;
	
	
	public function startup()
	{
		parent::startup();
		
		$a = strrpos($this->name, ':');
		
		if($a === FALSE)
		{
			$this->moduleName = '';
			$this->presenterName = $this->name;
		}
		else
		{
			$this->moduleName = substr($this->name, 0, $a + 1);
			$this->presenterName = substr($this->name, $a + 1);
		}
	}
	
	/**
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->moduleName;
	}
	
	/**
	 * @return string
	 */
	public function getPresenterName()
	{
		return $this->presenterName;
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getPageParam($key)
	{
		return isset($this->context->parameters['page'][$key]) ?
			$this->context->parameters['page'][$key] : NULL;
	}
	
	/**
	 * @param string|array snippet names
	 * @param string link destination in format "[[module:]presenter:]view" or "signal!"
	 * @param array|mixed
	 * @return void
	 */
	public function refresh($snippets = NULL, $destination = 'this', $args = array())
	{
		if($this->isAjax())
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
	 * @param string
	 * @param mixed
	 * @return string
	 */
	public function storeLink($link, $expiration = '+ 10 minutes')
	{
		$session = $this->getSession('Nette.Application/requests');
		
		do {
			$key = \Nette\Utils\Strings::random(5);
		} while(isset($session[$key]));
		
		$session[$key] = array($this->getUser()->getId(), $link);
		$session->setExpiration($expiration, $key);
		
		return $key;
	}
	
	/**
	 * @param string $name
	 * @return \Nette\ComponentModel\IComponent
	 */
	protected function createComponent($name)
	{
		$component = parent::createComponent($name);
		
		if($component instanceof Control && $this->translator !== NULL)
		{
			$component->setTranslator($this->translator);
		}
		
		return $component;
	}
	
	/**
	 * @param string $class
	 * @return \Nette\Templating\ITemplate
	 */
    protected function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);
		
		if($this->translator !== NULL)
		{
			$template->setTranslator($this->translator);
		}
		
		return $template;
    }
}