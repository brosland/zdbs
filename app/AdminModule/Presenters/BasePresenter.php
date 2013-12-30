<?php
namespace AdminModule;

abstract class BasePresenter extends \Brosland\Application\UI\SecurityPresenter
{

	public function startup()
	{
		parent::startup();

		if (!$this->user->isAllowed('administration'))
		{
			$this->checkAccessDeniedReason();
		}
	}
}