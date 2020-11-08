<?php

namespace App\Presenters;

use IPub\VisualPaginator\Components as VisualPaginator;
use Nette\Application\UI\Form;
use Tracy\Debugger;

class ProductPresenter extends BasePresenter
{
    /**
     * @var \App\Forms\ProductForm @inject
     */
    public $productFormFactory;

    /** @persistent string */
    public $product_name = NULL;

    /** @persistent string */
    public $product_count = NULL;

    /** @persistent string */
    public $price = NULL;

    /**
     * @var \App\Forms\ProductsFilterForm @inject
     */
    public $filterFormFactory;

    public function startup()
    {
        parent::startup();
    }


    public function actionDefault()
    {
        $products = $this->database->table('products')->where('count > ?', 0);

        if($this->product_name) {
            $products->where('products.title LIKE ?', "%$this->product_name%");
            $this['filterForm']['product_name']->setDefaultValue($this->product_name);
        }
        if($this->product_count) {
            $products->where('products.count LIKE ?', "%$this->product_count%");
            $this['filterForm']['product_count']->setDefaultValue($this->product_count);
        }
        if($this->price) {
            $products->where('products.price LIKE ?', "%$this->price%");
            $this['filterForm']['price']->setDefaultValue($this->price);
        }

        // Get visual paginator components
        $visualPaginator = $this['visualPaginator'];
        $paginator = $visualPaginator->getPaginator();
        $paginator->itemsPerPage = 2;
        $paginator->itemCount = $products->count('*');
        $products->limit($paginator->itemsPerPage, $paginator->offset);

        $this->template->products = $products;
    }


    public function actionDetail($productId)
    {
        $product = $this->database->table('products')->get($productId);
        if (!$product) {
            $this->flashMessage('Post ID do not exists');
            $this->redirect('default');
        }

        $this->id = $productId;

        $this['productAmountForm']->setDefaults($product->toArray());

        $this->template->product = $product;
        $this->template->productQuantity = $this->getSession('product')->quantity;
    }

    /**
     * Create items paginator
     *
     * @return VisualPaginator\Control
     */
    public function createComponentVisualPaginator()
    {
        // Init visual paginator
        $control = new VisualPaginator\Control;

        // To use bootstrap default template
        $control->setTemplateFile(APP_DIR . '/templates/_pagination.latte');

        $control->disableAjax();

        return $control;
    }


    protected function createComponentProductAmountForm(): Form
    {
        return $this->productFormFactory->create();
    }


    public function createComponentFilterForm()
    {
        return $this->filterFormFactory->create();
    }


    public function handleResetFilter()
    {
        $this->product_name = NULL;
        $this->product_count = NULL;
        $this->price = NULL;

        $this->redirect('this');
    }
}