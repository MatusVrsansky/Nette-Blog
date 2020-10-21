<?php

namespace App\Presenters;

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
        unset($this->getSession('basket')->products[$key]);

        // update totalPrice of basket
        $this->getSession('basket')->basketTotalPrice -= $productTotalPrice;

        $this->flashMessage('Product was removed from basket');
        $this->redirect('this');
    }


    public function handleReduceProductAmount($key, $productPrice)
    {
        if(--$this->getSession('basket')->products[$key]['product_total_amount'] == 0) {
            $this->getSession('basket')->products[$key]['product_total_amount'] = 1;
        } else {
            $this->getSession('basket')->products[$key]['product_total_amount'] -= 1;
            $this->getSession('basket')->products[$key]['product_total_price'] -= $productPrice;
            $this->getSession('basket')->basketTotalPrice -= $productPrice;
        }
        $this->redirect('this');
    }


    public function handleIncreaseProductAmount($key, $productPrice)
    {
        // update product total amount
        $this->getSession('basket')->products[$key]['product_total_amount'] += 1;

        // update product total price
        $this->getSession('basket')->products[$key]['product_total_price'] += $productPrice;

        // update basket total price
        $this->getSession('basket')->basketTotalPrice += $productPrice;

        $this->redirect('this');
    }
}
