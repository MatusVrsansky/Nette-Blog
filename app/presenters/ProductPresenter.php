<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class ProductPresenter extends BasePresenter
{
    /**
     * @var \App\Forms\ProductForm @inject
     */
    public $productFormFactory;

    public function startup()
    {
        parent::startup();
    }


    public function actionDefault()
    {
        $this->template->products = $this->database->table('products');
    }


    public function actionDetail($productId)
    {
        $product = $this->database->table('products')->get($productId);
        if (!$product) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('default');
        }

        $this['productAmountForm']->setDefaults($product);

        $this->template->product = $product;
        $this->template->productQuantity = $this->getSession('product')->quantity;
    }


    protected function createComponentProductAmountForm(): Form
    {
        return $this->productFormFactory->create();
    }
}
