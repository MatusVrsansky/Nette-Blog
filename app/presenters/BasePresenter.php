<?php

namespace App\Presenters;

use Nette;
use Tracy\Debugger;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context @inject
     */
    public $database;

    /**
     * @persistent int
     */
    public $id = NULL;


    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub

        // initialize session property -> basketTotalPrice
        if(!$this->getSession('basket')->basketTotalPrice) {
            $this->getSession('basket')->basketTotalPrice = 0;
        }

//        $this->getSession('basket')->basketTotalPrice = 0;
//        $this->getSession('basket')->products = [];

        $this->template->basketPrice = $this->getSession('basket')->basketTotalPrice;
    }

    public function updateTable($id, $count)
    {
        $this->database->table('products')->where(['id' => $id])->update(['count' => $count]);
    }

    public function getProductId($id) {
        return $this->database->table('products')->get($id);
    }
}
