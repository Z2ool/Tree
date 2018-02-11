<?php
/**
 * @author Petr Klezla
 * @date 10.02.2018
 */
namespace App\Presenters;



use Nette\Application\UI\Presenter;
use App\Model;

class InstallPresenter extends Presenter
{
    /**
     * @var Model\Tree
     */
    private $treeModel;


    public function __construct(Model\Tree $treeModel)
    {
        parent::__construct();
        $this->treeModel = $treeModel;
    }


    public function actionDefault()
    {
        $this->treeModel->install(__DIR__."/../install.sql");
        $this->redirect('Tree:default');
    }
}