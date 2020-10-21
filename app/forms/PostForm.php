<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class PostForm
{
    /**
     * @var
     */
    public $id;

    /**
     * @var \Nette\Database\Context
     */
    public $database;

    /**
     * @var \Nette\Security\User
     */
    public $user;

    /**
     * PostForm constructor.
     *
     * @param \Nette\Database\Context $database
     */
    public function __construct(\Nette\Database\Context $database,\Nette\Security\User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }


    public function create($id, $user)
    {
        $this->id = $id;
        $this->user = $user;

        $form = new Form;


        $form->getElementPrototype()->novalidate('novalidate');
        $form->getElementPrototype()->class('ajax');

        $form->addText('title', 'Title:')
            ->setRequired('Name is required');

        $form->addTextArea('content', 'Content:')
            ->setRequired('Content is required');

        $form->addHidden('user', $this->user);

        $form->addSubmit('send', 'Uložit a publikovat');

        $form->onError[] = [$this, 'postFormError'];
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }


    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function postFormError(Form $form)
    {
        $presenter = $form->getPresenter();

        if ($presenter->isAjax()) $presenter->redrawControl('contactFormSnippet');
    }

    public function postFormSucceeded(Form $form, array $values)
    {
        $presenter = $form->getPresenter();

        if ($this->id) {
            $post = $this->database->table('posts')->get($this->id);
            $post->update($values);
        } else {
            $this->database->table('posts')->insert($values);
        }

        $presenter->flashMessage('Post was published', 'success');
        $presenter->redirect('Homepage:default');
    }
}