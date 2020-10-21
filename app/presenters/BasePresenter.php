<?php

namespace App\Presenters;

use Nette;
/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context @inject
     */
    public $database;

    public function startup()
    {
        parent::startup(); // TODO: Change the autogenerated stub

        // initialize session property -> basketTotalPrice
        if(!$this->getSession('basket')->basketTotalPrice) {
            $this->getSession('basket')->basketTotalPrice = 0;
        }
        $this->template->basketPrice = $this->getSession('basket')->basketTotalPrice;
    }
}
