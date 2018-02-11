<?php
/**
 * @create Petr Klezla
 * @date 10.02.2018
 */

namespace App\Form;


use Nette\Application\UI\Form;
use Nette\SmartObject;
use App\Model;

/**
 * Class CreateForm
 * @package App\Form
 */
class CreateForm
{
    use SmartObject;

    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var Model\Tree
     */
    private $treeModel;

    /**
     * CreateForm constructor.
     * @param FormFactory $formFactory
     * @param Model\Tree $treeModel
     */
    public function __construct(FormFactory $formFactory,
                                Model\Tree $treeModel)
    {
        $this->formFactory = $formFactory;
        $this->treeModel = $treeModel;
    }

    /**
     * @param callable $onFinish
     * @return Form
     */
    public function create(callable $onFinish)
    {
        $form = $this->formFactory->create();
        $form->addText('parent_id','Přidat dítě (Zadejte Id rodiče)')
            ->setAttribute('type','text')
            ->setAttribute('placeholder','Zadejte ID rodiče')
            ->setRequired(false);
        $form->addSubmit('ok', 'Přidat');
        $form->onSuccess[] = function(Form $form, \stdClass $values){
            if(!($this->treeModel->get($values->parent_id) || is_null($values->parent_id)))
            {
                $form->addError('ID rodiče neexistuje');
            }
            $this->treeModel->insert($form->getValues(true));
        };
        $form->onSuccess[] = $onFinish;
        return $form;
    }
}