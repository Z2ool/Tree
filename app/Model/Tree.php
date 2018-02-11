<?php
/**
 * @create Petr Klezla
 * @date 10.02.2018
 */
namespace App\Model;

use Nette\Database\Context;
use Nette\Database\Helpers;
use Nette\Database\Table\IRow;
use Nette\SmartObject;

class Tree
{
    use SmartObject;
    /**
     * @var Context
     */
    private $context;

    /**
     * Tree constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return \Nette\Database\Table\Selection
     */
    public function getTable()
    {
        return $this->context->table('tree');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->getTable()->get($id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->context->beginTransaction();
        $tree = $this->get($id);
        $this->context->query('DELETE FROM tree WHERE tree.left >= ? AND tree.right <= ?',$tree->left, $tree->right);
        $r = $tree->right - $tree->left + 1;
        $this->context->query('UPDATE tree SET tree.left = tree.left - ? WHERE tree.left > ?',$r,$tree->right);
        $this->context->query('UPDATE tree SET tree.right = tree.right - ? WHERE tree.right > ?',$r,$tree->right);
        $this->context->commit();
    }

    /**
     * @param array $values
     */
    public function insert(array $values)
    {
        if (array_key_exists('parent_id',$values) && $values['parent_id']) {
            /** @var IRow|mixed $parent */
            $parent = $this->getTable()->get($values['parent_id']);
            $left  = $parent->right;
            $depth = $parent->depth + 1;
        } else {
            /*$max = $this->table->max('right');
            $left = $max ? $max + 1 : 1;*/
            $left = 1;
            $depth = 1;
        }

        $right = $left + 1;
        $values['depth'] = $depth;
        $values['left'] = $left;
        $values['right'] = $right;

        $this->move($left);
        $this->getTable()->insert($values);
    }

    /**
     * @param int $left
     */
    private function move($left)
    {
        $this->context->beginTransaction();
        $this->getTable()->where('tree.left>=?',$left)->update(['tree.left+=' => 2]);
        $this->getTable()->where('tree.right>=?',$left)->update(['tree.right+=' => 2]);
        $this->context->commit();
    }

    /**
     * @return $IRow|array
     */
    public function getAll($fordepth = null)
    {
        $data = $this->getTable()->order('tree.left');
        if($fordepth)
            $data = $this->depth($data);
        return $data;
    }
    /**
     * @return $IRow
     */
    public function isTree()
    {
        return $this->getTable()->fetch();
    }

    /**
     * @param $tree
     * @return array
     */
    private function depth($tree)
    {
        $data = [];
        foreach ($tree as $node)
        {
            $data[$node->depth][] = [
                'parent'=>$node->parent_id,
                'id'=>$node->id
            ];
        }
        return $data;
    }

    /**
     * @param $file
     */
    public function install($file)
    {
        Helpers::loadFromFile($this->context->getConnection(), $file);
    }

    public function isTable()
    {
        return $this->context->query('SHOW TABLES LIKE ?','tree')->fetch();
    }

}