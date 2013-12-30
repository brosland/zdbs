<?php
namespace CertificatesModule\AdminModule\Forms;

use CertificatesModule\Models\ParamType\ParamType,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateTypeForm extends \Brosland\Application\UI\EntityForm
{
	/**
	 * @var array
	 */
	private static $REQUIRED_OPTIONS = array(
		TRUE => 'Áno', FALSE => 'Nie'
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
		$entity = $this->entity;
		
		$this->getElementPrototype()->id = 'certificate-type-form';
		
		$this->addGroup('Certifikát');
		$this->addText('name', 'Názov', 64, 255)
			->setRequired()
			->addRule(function($control) use($certificateTypeDao, $entity) {
				$name = $control->getValue();
				return $entity !== NULL && $name === $entity->getName()
					|| !$certificateTypeDao->findOneBy(array('name' => $name));
			});
		$this->addTextArea('description', 'Popis');
		$this->addTextArea('template', 'Šablóna výpisu detailu');
		$this->addEntitySelect('category', 'Kategória', NULL, 'name')
			->setItems($this->certificateTypeDao->related('category')->findAll())
			->setPrompt('Vyberte kategóriu')
			->setRequired();

		$paramTypeDao = $this->certificateTypeDao->related('paramTypes');

		$this->addGroup('Parametre certifikátu');
		$replicator = $this->addDynamic('paramTypes', function($paramType) {
			$paramType->addHidden('order');
			$paramType->addText('name', 'Názov', 64, 255)
				->setRequired();
			$paramType->addText('label', 'Názov parametra', 64, 255)
				->setRequired();
			$paramType->addSelect('paramTypeId', 'Typ parametra', ParamType::getValues())
				->setRequired()
				->setPrompt('Vyberte možnosť');
			$paramType->addRadioList('required', 'Vyžadovaný', self::$REQUIRED_OPTIONS)
				->setRequired();
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

		$this->setCurrentGroup();
		$this->addSubmit('save', 'Ulož');
	}
}