<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class PostPresenter extends BasePresenter
{
    /**
     * @persistent int
     */
    public $id = NULL;

    /**
     * @persistent user
     */
    public $user = NULL;

    /**
     * @var \App\Forms\PostForm @inject
     */
    public $postFormFactory;

    /**
     * @var \App\Forms\CommentsForm @inject
     */
    public $commentsFormFactory;

    public function startup()
    {
        parent::startup();
    }


    public function actionDetail($postId)
    {
        $post = $this->database->table('posts')->get($postId);
        if (!$post) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('Homepage:');
        }

        $this->id = $postId;

        $this->template->post = $post;
        $this->template->comments = $post->related('comment')->order('created_at');
    }


    public function actionAdd()
    {
        $this->id = NULL;
        $this->user = $this->getUser()->id;

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }


    public function actionEdit($postId)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $this->id = $postId;

        $post = $this->database->table('posts')->get($postId);
        if (!$post) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('Homepage:');
        }

        $this['postForm']->setDefaults($post);
    }


    public function createComponentPostForm(): Form
    {
        return $this->postFormFactory->create($this->id, $this->user);
    }


    protected function createComponentCommentsForm(): Form
    {
        return $this->commentsFormFactory->create($this->id);
    }
}
