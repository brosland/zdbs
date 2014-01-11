<?php
namespace CertificatesModule\AdminModule\Components;

use Brosland\Components\Table\Models\DoctrineModel,
	Brosland\Components\Table\Table,
	Doctrine\ORM\QueryBuilder,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateTypeTable extends Table
{
	/**
	 * @var EntityDao
	 */
	private $certificateTypeDao;


	/**
	 * @param EntityDao $certificateTypeDao
	 * @param QueryBuilder $queryBuilder
	 */
	public function __construct(EntityDao $certificateTypeDao, QueryBuilder $queryBuilder)
	{
		parent::__construct(new DoctrineModel($this, $queryBuilder));

		$this->certificateTypeDao = $certificateTypeDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		// columns
		$this->addColumn('name', 'Názov');
		$this->addColumn('description', 'Popis')
			->maxLength = 64;
		$this->addColumn('category', 'Kategória', 'category.name');
		$this->addColumn('codePrefix', 'Prefix kódu', 'category.codePrefix');

		// actions
		$this->addAction('edit', 'Editovať')
			->setIcon('ui-icon-pencil')
			->setLink(callback(function($certificateType) use($presenter) {
					return $presenter->link(':Certificates:Admin:CertificateType:edit', $certificateType->getId());
				}));
		$this->addAction('import', 'Import certifikátov')
			->setIcon('ui-icon-arrowthick-1-n')
			->setLink(callback(function($certificateType) use($presenter) {
					return $presenter->link(':Certificates:Admin:Certificate:import', $certificateType->getId());
				}));
		$this->addAction('export', 'Export certifikátov')
			->setIcon('ui-icon-arrowthick-1-s')
			->setLink(callback(function($certificateType) use($presenter) {
					return $presenter->link(':Certificates:Admin:Certificate:export', $certificateType->getId());
				}));

		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteCertificateTypes');

		// sorting
		$this->setSortTypes(array(
			'name' => 'názvu',
			'description' => 'popisu',
			'category.codePrefix' => 'prefixu kódu',
			'category.name' => 'kategórie'
		));

		$this->setDefaultSorting(array('name' => 'asc'));
	}

	/**
	 * @param \Nette\Forms\Controls\SubmitButton
	 */
	public function deleteCertificateTypes(\Nette\Forms\Controls\SubmitButton $button)
	{
		$certificateTypes = $button->getForm()->getSelectedItems();

		if (!empty($certificateTypes))
		{
			$this->certificateTypeDao->delete($certificateTypes);
			$this->flashMessage('Typy certifikátov boli zmazané.', 'success');
		}
	}
}