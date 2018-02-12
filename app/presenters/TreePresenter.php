<?php
/**
 * @author Petr Klezla
 * @date 10.02.2018
 */
namespace App\Presenters;



use App\Form\CreateForm;
use Nette\Application\UI\Presenter;
use App\Model;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Tracy\Debugger;

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
    /** @var Selection|IRow|array */
    private $items;

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

    public function actionDefault()
    {
        $this->items = $this->treeModel->getAll(true);
    }

    public function renderDefault()
    {
        $this->template->items = $this->items;
    }

    public function actionInit()
    {
        $tree = $this->treeModel->getAll(true);
        $this->sendJson($tree);
    }
    public function actionDelete($id)
    {
        $tree = $this->treeModel->getAllNode($id);
        $this->treeModel->delete($id);
        $depthCurrent = $this->treeModel->isDepth($tree['depth']);
        $tree['depth'] = array_diff($tree['depth'], $depthCurrent);
        //Debugger::barDump($tree);
        //$tree = $this->treeModel->getAll(true);
        $this->sendJson($tree);
    }
    public function actionInsert($id = null)
    {
        if((!$this->treeModel->isTree() && is_null($id)) || $this->treeModel->get($id))
        {
            $row = $this->treeModel->insert(['parent_id'=>$id]);
            $data = [];
            if($this->treeModel->getCountDepth($row->depth) > 1)
            {
                $data['add']['depth'] = $row->depth;
                /** @var ActiveRow $item */
                foreach ($this->treeModel->getAllByDepth($row->depth) as $item)
                {
                    $data['add']['data'][] = $item->toArray();
                }
            }
            else
                $data['new'] = $row->toArray();
            //$tree = $this->treeModel->getAll(true);
            $this->sendJson($data);
        }
        else
        {
            $this->sendJson(['error'=>"Rodičovské ID: ".$id." neexistuje."]);
        }
    }
}