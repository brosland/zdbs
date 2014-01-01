<?php
namespace Brosland\Application\UI;

use Brosland\Forms\Controls,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter,
	Nette\Forms\Container;

abstract class Form extends \Nette\Application\UI\Form
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setRenderer(new \Kdyby\BootstrapFormRenderer\BootstrapRenderer());
	}
	
	/**
	 * @param \Nette\ComponentModel\IComponent $obj
	 */
	protected function attached($obj)
	{
		parent::attached($obj);
		
		if(!$obj instanceof IPresenter)
		{
			return;
		}
		
		$this->configure($obj);
	}
	
	/**
	 * @param Ipresenter $presenter
	 */
	protected abstract function configure(IPresenter $presenter);
}

Controls\AntispamControl::register();

Container::extensionMethod('addDatePicker', function($_this, $name, $label, $cols = 10, $maxLength = 10) {
	return $_this[$name] = new Controls\DatePicker($label, $cols, $maxLength);
});

Container::extensionMethod('addEntityAutocompleteTextInput', function($_this, $name, EntityDao $service, $nameKey, $label = NULL, $cols = NULL) {
	return $_this[$name] = new Controls\EntityAutocompleteTextInput($service, $nameKey, $label, $cols);
});

Container::extensionMethod('addEntitySelect', function($_this, $name, $label = NULL, array $entities = NULL, $nameKey = 'name') {
      return $_this[$name] = new Controls\EntitySelectBox($label, $entities, $nameKey);
});

Container::extensionMethod('addEntityMultiSelect', function($_this, $name, $label = NULL, array $entities = NULL, $size = NULL, $nameKey = 'name') {
	return $_this[$name] = new Controls\EntityMultiSelectBox($label, $entities, $size, $nameKey);
});