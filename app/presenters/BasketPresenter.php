<?php

namespace App\Presenters;

use Tracy\Debugger;

class BasketPresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
    }


    public function actionDefault()
    {
        $this->template->basketProducts = $this->getSession('basket')->products;
    }


    public function handleRemoveProductFromBasket($key, $productTotalPrice)
    {
        // get product from table -> products
        $product = $this->getProductId($this->getSession('basket')->products[$key]['id']);

        // update table -> products
        $this->updateTable($this->getSession('basket')->products[$key]['id'], $product->count + $this->getSession('basket')->products[$key]['amount']);

        unset($this->getSession('basket')->products[$key]);

        // update totalPrice of basket
        $this->getSession('basket')->basketTotalPrice -= $productTotalPrice;

        $this->flashMessage('Product was removed from basket');
        $this->redirect('this');
    }


    public function handleReduceProductAmount($key, $productPrice)
    {
        if($this->getSession('basket')->products[$key]['amount'] - 1 == 0) {
            $this->getSession('basket')->products[$key]['amount'] = 1;
        } else {
            // get product from table -> products
            $product = $this->getProductId($this->getSession('basket')->products[$key]['id']);

            // update table -> products
            $this->updateTable( $this->getSession('basket')->products[$key]['id'], $product->count + 1);

            $this->getSession('basket')->products[$key]['amount'] -= 1;
            $this->getSession('basket')->products[$key]['total_price'] -= $productPrice;
            $this->getSession('basket')->basketTotalPrice -= $productPrice;
        }
        $this->redirect('this');
    }


    public function handleIncreaseProductAmount($id, $productPrice)
    {
        // get product from table -> products
        $product = $this->getProductId($this->getSession('basket')->products[$id]['id']);

        if($product->count != 0) {
            // update product total count in stock
            $this->getSession('basket')->products[$id]['count'] -= 1;

            // update table -> products
            $this->updateTable( $this->getSession('basket')->products[$id]['id'], $product->count - 1);

            // update product total amount
            $this->getSession('basket')->products[$id]['amount'] += 1;

            // update product total price
            $this->getSession('basket')->products[$id]['total_price'] += $productPrice;

            // update basket total price
            $this->getSession('basket')->basketTotalPrice += $productPrice;
        } else {
            $this->flashMessage('Insufficient products in stock');
        }

        $this->redirect('this');
    }
}
