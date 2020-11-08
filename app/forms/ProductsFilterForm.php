<?php


namespace App\Forms;

use Nette\Application\UI\Form;

class ProductsFilterForm
{
    /**
     * @return \Nette\Application\UI\Form
     */
    public function create()
    {
        $form = new Form;

        $form->addText('product_name');
        $form->addText('product_count')
            ->addRule($form::FLOAT, 'Enter valid count please');
        $form->addText('price')
            ->addRule($form::FLOAT, 'Please enter valid price!');
        $form->addSubmit('filter', 'Filter');

        $form->onSuccess[] = [$this, 'filterFormSubmitted'];

        return $form;
    }


    /**
     * @param \Nette\Application\UI\Form $form
     * @param \Nette\Utils\ArrayHash $values
     */
    public function filterFormSubmitted($form, $values)
    {
        $presenter = $form->getPresenter();

        $presenter->product_name = $values->product_name;
        $presenter->product_count = $values->product_count;
        $presenter->price = $values->price;

        $presenter->redirect('Product:default');
    }
}
