<?php
/**
 * @create Petr Klezla
 * @date 10.02.2018
 */

namespace App\Form;


use Nette\Forms\Form;

/**
 * Class FormFactory
 * @package App\Form
 */
class FormFactory
{
    /**
     * @return Form
     */
    public function create()
    {
        $form = new Form();
        return $form;
    }
}