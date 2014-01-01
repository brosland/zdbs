<?php
namespace AdminModule\Components\Tables;

use Brosland\Components\Table\Models\DoctrineModel,
	Brosland\Components\Table\Table,
	Kdyby\Doctrine\EntityDao,
	Nette\Application\IPresenter;

class UserTable extends Table
{
	/** @var EntityDao */
	private $userDao;
	
	
	/**
	 * @param EntityDao $userDao
	 */
	public function __construct(EntityDao $userDao)
	{
		$queryBuilder = $userDao->createQueryBuilder('user')
			->leftJoin('user.roles', 'roles')
			->groupBy('user.id');
		
		parent::__construct(new DoctrineModel($this, $queryBuilder));
		
		$this->userDao = $userDao;
	}
	
	/**
	 * @param IPresenter $presenter
	 */
	protected function configure(IPresenter $presenter)
	{
		// columns
		$this->addColumn('name', 'Meno');
		$this->addColumn('surname', 'Priezvisko');
		$this->addColumn('email', 'Email');
		$this->addColumn('registered', 'Dátum registrácie')
			->dateFormat = 'd.m.Y, H:i';
		$this->addColumn('lastLog', 'Posledné prihlásenie')
			->dateFormat = 'd.m.Y, H:i';
		$this->addColumn('roles', 'Roly', 'roles');
		
		// sorting
		$this->setSortTypes(array(
			'name' => 'mena',
			'surname' => 'priezviska',
			'email' => 'emailu',
			'registered' => 'dátumu registrácie',
			'lastLog' => 'posledného príhlásenia',
			'roles' => 'podľa roly'
		));
		
		$this->setDefaultSorting(array('surname' => 'asc', 'name' => 'asc'));
	}
	
	/**
	 * @param string
	 * @return \Nette\Templating\FileTemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate();
		
		$parentTemplate = $template->getFile();
		$template->setFile(__DIR__ . '/templates/userTable.latte');
		$template->parentTemplate = $parentTemplate;
		
		return $template;
	}
}