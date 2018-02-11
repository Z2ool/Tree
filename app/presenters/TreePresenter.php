<?php
/**
 * @author Petr Klezla
 * @date 10.02.2018
 */
namespace App\Presenters;



use App\Form\CreateForm;
use Nette\Application\UI\Presenter;
use App\Model;

class TreePresenter extends Presenter
{
    /**
     * @var Model\Tree
     */
    private $treeModel;
    /**
     * @var CreateForm
     */
    private $createForm;

    public function __construct(Model\Tree $treeModel,
                                CreateForm $createForm)
    {
        parent::__construct();
        $this->treeModel = $treeModel;
        $this->createForm = $createForm;
    }

    public function startup()
    {
        parent::startup();
        if(!$this->treeModel->isTable())
        {
            $this->redirect('Install:');
        }
    }

    protected function createComponentForm()
    {
        return $this->createForm->create(function(){
            if($this->isAjax())
            {
                $tree = $this->treeModel->getAll(true);
                $this->sendJson($tree);
            }
        });

    }

    public function actionInit()
    {
        $tree = $this->treeModel->getAll(true);
        $this->sendJson($tree);
    }
    public function actionDelete($id)
    {
        $this->treeModel->delete($id);
        $tree = $this->treeModel->getAll(true);
        $this->sendJson($tree);
    }
    public function actionInsert($id = null)
    {
        if((!$this->treeModel->isTree() && is_null($id)) || $this->treeModel->get($id))
        {
            $this->treeModel->insert(['parent_id'=>$id]);
            $tree = $this->treeModel->getAll(true);
            $this->sendJson($tree);
        }
        else
        {
            $this->sendJson(['error'=>"Rodičovské ID: ".$id." neexistuje."]);
        }
    }

    public function actionInstall()
    {
        $this->treeModel->install(__DIR__."/../install.sql");
        $this->redirect('default');
    }
}