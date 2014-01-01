<?php
namespace CertificatesModule\AdminModule\Components;

use Brosland\Components\Table\Models\DoctrineModel,
	Brosland\Components\Table\Table,
	Doctrine\ORM\QueryBuilder,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CategoryTable extends Table
{
	/**
	 * @var EntityDao
	 */
	private $categoryDao;


	/**
	 * @param EntityDao $categoryDao
	 * @param QueryBuilder $queryBuilder
	 */
	public function __construct(EntityDao $categoryDao, QueryBuilder $queryBuilder)
	{
		parent::__construct(new DoctrineModel($this, $queryBuilder));

		$this->categoryDao = $categoryDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		// columns
		$this->addColumn('name', 'Meno');
		$this->addColumn('codePrefix', 'Prefix kódu');
		$this->addColumn('description', 'Popis')
			->maxLength = 64;
		$this->addColumn('certificateTypes', 'Počet typov certifikátov');

		// actions
		$this->addAction('edit', 'Editovať')
			->setIcon('ui-icon-pencil')
			->setLink(callback(function($category) use($presenter) {
					return $presenter->link('Category:edit', $category->id);
				}));

		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteCategories');

		// sorting
		$this->setSortTypes(array(
			'name' => 'mena',
			'codePrefix' => 'prefixu kódu',
			'description' => 'popisu',
			'certificateTypes' => 'počtu typov certifikátov'
		));

		$this->setDefaultSorting(array('name' => 'asc'));
	}

	/**
	 * @param \Nette\Forms\Controls\SubmitButton
	 */
	public function deleteCategories(\Nette\Forms\Controls\SubmitButton $button)
	{
		$categories = $button->getForm()->getSelectedItems();

		if (!empty($categories))
		{
			$this->categoryDao->delete($categories);
			$this->flashMessage('Kategórie boli zmazané.', 'success');
		}
	}
}