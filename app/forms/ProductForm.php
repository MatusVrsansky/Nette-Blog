<?php

namespace App\Forms;

use Nette\Application\UI\Form;

class ProductForm
{
    public function create()
    {
        $form = new Form;

        $form->getElementPrototype()->novalidate('novalidate');
        $form->getElementPrototype()->class('ajax');

        $form->addHidden('title');
        $form->addHidden('price');

        $form->addInteger('amount')
            ->setHtmlAttribute('class', 'mb-4')
        ->setDefaultValue(1)
            ->addRule($form::RANGE, 'Number between 1 - 1000', [1, 100]);

        $form->addSubmit('send', 'Add to card')
        ->setHtmlAttribute('class', 'btn btn-primary btn-small');

        // call method signInFormSucceeded() on success
        $form->onError[] = [$this, 'productFormError'];
        $form->onSuccess[] = [$this, 'productFormSuccess'];
        return $form;
    }


    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function productFormError(Form $form) {
        $form->getPresenter()->redrawControl('productFormSnippet');
    }


    public function productFormSuccess(Form $form, array $values)
    {
        // initialize session and add first product
        if(!$form->getPresenter()->getSession('basket')->products) {
            $form->getPresenter()->getSession('basket')->products = [];

            array_push($form->getPresenter()->getSession('basket')->products, [
                'product_title' => $values['title'],
                'product_price' => $values['price'],
                'product_total_amount' => $values['amount'],
                'product_total_price' =>$values['price'] * $values['amount']
            ]);
        } else {
            array_push($form->getPresenter()->getSession('basket')->products, [
                'product_title' => $values['title'],
                'product_price' => $values['price'],
                'product_total_amount' => $values['amount'],
                'product_total_price' =>$values['price'] * $values['amount']
            ]);
        }

        $form->getPresenter()->getSession('basket')->basketTotalPrice += $values['price'] * $values['amount'];

        $form->getPresenter()->flashMessage('The product was successfully added to the cart');
        $form->getPresenter()->redirect('Product:default');
    }
}