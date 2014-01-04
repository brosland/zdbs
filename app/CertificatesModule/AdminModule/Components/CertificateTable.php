<?php
namespace CertificatesModule\AdminModule\Components;

use Brosland\Components\Table\Models\DoctrineModel,
	Brosland\Components\Table\Table,
	Doctrine\ORM\QueryBuilder,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class CertificateTable extends Table
{
	/**
	 * @var EntityDao
	 */
	private $certificateDao;


	/**
	 * @param EntityDao $certificateDao
	 * @param QueryBuilder $queryBuilder
	 */
	public function __construct(EntityDao $certificateDao, QueryBuilder $queryBuilder)
	{
		parent::__construct(new DoctrineModel($this, $queryBuilder));

		$this->certificateDao = $certificateDao;
	}

	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		// columns
		$this->addColumn('code', 'Kód certifikátu');
		$this->addColumn('codePrefix', 'Prefix kódu', 'certificateType.category.codePrefix');
		$this->addColumn('created', 'Dátum vytvorenia')
			->dateFormat = 'j.n.Y';
		$this->addColumn('expiration', 'Dátum expirácie')
			->dateFormat = 'j.n.Y';
		$this->addColumn('certificateType', 'Typ certifikátu', 'certificateType.name');
		$this->addColumn('category', 'Kategória', 'certificateType.category.name');

		// actions
		$this->addAction('detail', 'Detail')
			->setIcon('ui-icon-zoomin')
			->setLink(callback(function($certificate) use($presenter) {
					return $presenter->link('Certificate:default', $certificate->getId());
				}));
		$this->addAction('edit', 'Editovať')
			->setIcon('ui-icon-pencil')
			->setLink(callback(function($certificate) use($presenter) {
					return $presenter->link('Certificate:edit', $certificate->getId());
				}));

		// toolbar
		$this->addToolbarButton('delete', 'Zmazať')
			->onClick[] = callback($this, 'deleteCertificates');

		// sorting
		$this->setSortTypes(array(
			'code' => 'kódu',
			'certificateType.category.codePrefix' => 'prefixu kódu',
			'created' => 'dátumu vytvorenia',
			'expiration' => 'dátum expirácie',
			'certificateType.name' => 'typu certifikátu',
			'category.certificateType.category.name' => 'kategórie'
		));

		$this->setDefaultSorting(array('created' => 'desc'));
	}

	/**
	 * @param \Nette\Forms\Controls\SubmitButton
	 */
	public function deleteCertificates(\Nette\Forms\Controls\SubmitButton $button)
	{
		$certificates = $button->getForm()->getSelectedItems();

		if (!empty($certificates))
		{
			$this->certificateDao->delete($certificates);
			$this->flashMessage('Certifikáty boli zmazané.', 'success');
		}
	}
}