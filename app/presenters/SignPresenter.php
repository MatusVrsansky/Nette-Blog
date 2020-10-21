<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter
{
    /**
     * @var \App\Forms\SignInForm @inject
     */
    public $signInFormFactory;

    public function startup()
    {
        parent::startup();
    }


	protected function createComponentSignInForm(): Form
	{
        return $this->signInFormFactory->create();
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('Homepage:');
	}
}
