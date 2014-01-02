<?php
namespace CertificatesModule\AdminModule\Forms;

use CertificatesModule\Models\ParamType\ParamType,
	Kdyby\Doctrine\EntityDao,
	Nette\Forms\Controls\BaseControl,
	Nette\Application\IPresenter;

class CertificateTypeForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var array
	 */
	private static $REQUIRED_OPTIONS = array(
		1 => 'Áno', 0 => 'Nie'
	);
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;


	/**
	 * @param EntityDao $certificateTypeDao
	 */
	public function __construct(EntityDao $certificateTypeDao)
	{
		parent::__construct();

		$this->certificateTypeDao = $certificateTypeDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		$certificateTypeDao = $this->certificateTypeDao;
		
		$this->getElementPrototype()->id = 'certificate-type-form';
		
		$this->addGroup('Certifikát');
		$this->addText('name', 'Názov', 64, 255)
			->setRequired()
			->addRule(function(BaseControl $control) use($certificateTypeDao) {
				$form = $control->getForm();
				$name = $control->getValue();
				return $form->hasEntity() && $name === $form->getEntity()->getName()
					|| !$certificateTypeDao->findOneBy(array('name' => $name));
			}, 'Typ certifikátu s rovnakým názvom už existuje.');
		$this->addTextArea('description', 'Popis');
		$this->addTextArea('template', 'Šablóna výpisu detailu')
			->setRequired();
		$this->addEntitySelect('category', 'Kategória', NULL, 'name')
			->setItems($this->certificateTypeDao->related('category')->findAll())
			->setPrompt('Vyberte kategóriu')
			->setRequired();

		$paramTypeDao = $this->certificateTypeDao->related('paramTypes');

		$this->addGroup('Parametre certifikátu');
		$replicator = $this->addDynamic('paramTypes', function($paramType) {
			$paramType->addText('name', 'Názov', 64, 255)
				->setRequired()
				->getControlPrototype()->addAttributes(array('class' => 'name-field'));
			$paramType->addText('label', 'Názov parametra', 64, 255)
				->setRequired()
				->getControlPrototype()->addAttributes(array('class' => 'label-field'));
			$paramType->addSelect('paramTypeId', 'Typ parametra', ParamType::getValues())
				->setRequired()
				->setPrompt('Vyberte možnosť');
			$paramType->addRadioList('required', 'Vyžadovaný', self::$REQUIRED_OPTIONS)
				->setValue(0)
				->setRequired()
				->getControlPrototype()->addAttributes(array('class' => 'required-field'));
			$paramType->addSubmit('remove', 'Odstrániť')
				->setAttribute('class', 'ajax remove-button')
				->setValidationScope(FALSE)
				->addRemoveOnClick(function() use($paramType) {
					$presenter = $paramType->getForm()->getPresenter();
					$presenter->invalidateControl($paramType->getForm()->getName());
				});
		}, 1, TRUE);

		$replicator->addSubmit('add', 'Pridať parameter')
			->setValidationScope(FALSE)
			->setAttribute('class', 'ajax add-button')
			->addCreateOnClick(function($replicator, $paramType) {
				$presenter = $paramType->getForm()->getPresenter();
				$presenter->invalidateControl($paramType->getForm()->getName());
			});
		
		$this->addButton('generateTemplate', 'Generovať šablónu');
		
		$this->setCurrentGroup();
		$this->addSubmit('save', 'Ulož');
	}
}