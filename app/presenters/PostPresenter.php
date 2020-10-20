<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class PostPresenter extends Nette\Application\UI\Presenter
{

	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	public function renderShow(int $postId): void
	{
		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->error('Post not found');
		}

		$this->template->post = $post;
		$this->template->comments = $post->related('comment')->order('created_at');
	}


	protected function createComponentCommentForm(): Form
	{
		$form = new Form;
		$form->addText('name', 'Your name:')
			->setRequired();

		$form->addEmail('email', 'Email:');

		$form->addTextArea('content', 'Comment:')
			->setRequired();

		$form->addSubmit('send', 'Publish comment');
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];

		return $form;
	}


	public function commentFormSucceeded(Form $form, \stdClass $values): void
	{
		$this->database->table('comments')->insert([
			'post_id' => $this->getParameter('postId'),
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content,
		]);

		$this->flashMessage('Thank you for your comment', 'success');
		$this->redirect('this');
	}


	public function actionCreate(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}


	public function actionEdit(int $postId): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->flashMessage('Post ID do not exists');
			$this->redirect('Homepage:');
		}
		Debugger::barDump($post);

		$this['postForm']->setDefaults($post->toArray());
	}

    public function handleRemove($postId)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

       $post = $this->database->table('posts')->get($postId);

        if (!$post) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('Homepage:default');
        }

        $this->database->table('comments')->where(['post_id' => $postId])->delete();
        $post->delete();

        $this->flashMessage('Post deleted successfully');
        $this->redirect('Homepage:default');
    }


	protected function createComponentPostForm(): Form
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->error('You need to log in to create or edit posts');
		}

		$form = new Form;

		$form->addText('title', 'Title:')
			->setRequired('Nazov je povinny');

		$form->addTextArea('content', 'Content:')
            ->setRequired('Obsah je povinny');

        $form->addHidden('user', $this->getUser()->id);

        $form->addSubmit('send', 'Uložit a publikovat');

        $form->onError[] = [$this, 'contactFormError'];
		$form->onSuccess[] = [$this, 'postFormSucceeded'];

		return $form;
	}

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function contactFormError(Form $form) {
        $form->getPresenter()->redrawControl('contactFormSnippet');
    }

	public function postFormSucceeded(Form $form, array $values): void
	{
		$postId = $this->getParameter('postId');

		if ($postId) {
			$post = $this->database->table('posts')->get($postId);
			$post->update($values);
		} else {
			$post = $this->database->table('posts')->insert($values);
		}

		$this->flashMessage('Post was published', 'success');
		$this->redirect('Homepage:default');
	}
}
