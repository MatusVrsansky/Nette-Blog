<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use Tracy\Debugger;

class ProductForm
{
    public function create()
    {
        $form = new Form;

        $form->addHidden('id');
        $form->addHidden('title');
        $form->addHidden('price');
        $form->addHidden('count');

        $form->addInteger('amount')
            ->setHtmlAttribute('class', 'mb-4')
        ->setDefaultValue(1)
            ->addRule($form::RANGE, 'Only number between 1 - 100', [1, 100])
            ->setRequired('Amount is required');

        $form->addSubmit('send', 'Add to card')
        ->setHtmlAttribute('class', 'btn btn-primary btn-small');

        // call method signInFormSucceeded() on success
        $form->onSuccess[] = [$this, 'productFormSuccess'];
        return $form;
    }


    public function productFormSuccess(Form $form, array $values)
    {
        if($values['count'] < $values['amount']) {
            $form->getPresenter()->flashMessage('There are not enough pieces of this product in stock!');
            $form->getPresenter()->redirect('this');
        }

        else {
            $values['total_price'] = $values['price'] * $values['amount'];

            // update total count of product in table -> products
            $values['count'] -= $values['amount'];

            // initialize session and add first product
            if(!$form->getPresenter()->getSession('basket')->products) {
                $form->getPresenter()->getSession('basket')->products = [];

                array_push($form->getPresenter()->getSession('basket')->products,
                    $values
                );
            } else {
                array_push($form->getPresenter()->getSession('basket')->products,
                    $values
                );
            }

            // update table -> products
            $form->getPresenter()->updateTable($values['id'], $values['count']);

            $form->getPresenter()->getSession('basket')->basketTotalPrice += $values['price'] * $values['amount'];

            $form->getPresenter()->flashMessage('The product was successfully added to the cart');
            $form->getPresenter()->redirect('Product:default');
        }
    }

}