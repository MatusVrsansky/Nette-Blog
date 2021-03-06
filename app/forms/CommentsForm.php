<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class CommentsForm
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
     * CommentsForm constructor.
     *
     * @param \Nette\Database\Context $database
     */
    public function __construct(\Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    /**
     * @return \Nette\Application\UI\Form
    **/
    public function create($id)
    {
        $this->id = $id;

        $form = new Form;

        $form->addText('name', 'Your name:')
            ->setRequired('Name is required');

        $form->addEmail('email', 'Email:')
            ->setRequired('Email is required');

        $form->addTextArea('content', 'Comment:')
            ->setRequired('Comment text is required');

        $form->addSubmit('send', 'Publish comment');

        $form->onSuccess[] = [$this, 'commentFormSucceeded'];

        return $form;
    }


    public function commentFormSucceeded(Form $form, \stdClass $values)
    {
        $presenter = $form->getPresenter();

        $this->database->table('comments')->insert([
            'post_id' => $this->id,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]);

        $presenter->flashMessage('Thank you for your comment', 'success');
        $presenter->redirect('Post:detail', $this->id);
    }
}