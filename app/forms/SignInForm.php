<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette;

class SignInForm
{
    /**
     * @var
     */
    public $id;
    public function create()
    {
        $form = new Form;

        $form->getElementPrototype()->novalidate('novalidate');
        $form->getElementPrototype()->class('ajax');


        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username.');

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');

        $form->addSubmit('send', 'Sign in');

        // call method signInFormSucceeded() on success
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function signInForError(Form $form) {
        $form->getPresenter()->redrawControl('signInFormSnippet');
    }

    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $form->getPresenter()->getUser()->login($values->username, $values->password);
            $form->getPresenter()->flashMessage('You have been logged in successfully');
            $form->getPresenter()->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Incorrect username or password.');
        }
    }
}