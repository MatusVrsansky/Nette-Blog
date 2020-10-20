<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class SignPresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var \App\Forms\SignInForm @inject
     */
    public $signInFormFactory;

	/**
	 * Sign-in form factory.
	 */
	protected function createComponentSignInForm(): Form
	{
        return $this->signInFormFactory->create();
	}


	public function actionOut(): void
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('Homepage:');
	}
}
