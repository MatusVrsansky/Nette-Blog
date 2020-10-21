<?php

namespace App\Presenters;

class HomepagePresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
    }


    public function renderDefault(int $page = 1)
    {
        if($this->getUser()->isLoggedIn())
        {
            $this->template->posts = $this->database->table('posts')
                ->order('created_at DESC')
                ->page($page, 5)
                ->where(['user' => $this->getUser()->id]);
        } else {
            $this->template->posts =$this->database->table('posts')
                ->order('created_at DESC')
                ->page($page, 5);
        }

        $this->template->page = $page;
    }


    public function handleRemove($postId)
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }

        $post = $this->database->table('posts')->get($postId);

        if (!$post) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('default');
        }

        $this->database->table('comments')->where(['post_id' => $postId])->delete();
        $post->delete();

        $this->flashMessage('Post deleted successfully');
        $this->redirect('default');
    }
}
