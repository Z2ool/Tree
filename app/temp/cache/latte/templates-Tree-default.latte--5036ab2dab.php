<?php
// source: C:\xampp1\htdocs\tree\app\presenters/templates/Tree/default.latte

use Latte\Runtime as LR;

class Template5036ab2dab extends Latte\Runtime\Template
{
	public $blocks = [
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'title' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('title', get_defined_vars());
?>

<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockTitle($_args)
	{
?>    Strom
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div class="alert" role="alert">

</div>
<div class="container tree"
     data-data="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Tree:init")) ?>"
     data-insert="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Tree:insert")) ?>"
     data-delete="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Tree:delete")) ?>">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center"><?php
		$this->renderBlock('title', get_defined_vars(), function ($s, $type) {
			$_fi = new LR\FilterInfo($type);
			return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striptags', $_fi, $s));
		});
?></h1>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <?php
		/* line 24 */
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["form"], ['class'=>'form-inline ajax']);
?>

            <div class="form-group mx-sm-3 mb-2 message">
                <?php if ($_label = end($this->global->formsStack)["parent_id"]->getLabel()) echo $_label->addAttributes(['class'=>'sr-only']) ?>

                <?php echo end($this->global->formsStack)["parent_id"]->getControl()->addAttributes(['class'=>'form-control']) /* line 27 */ ?>

            </div>
            <button type="submit" class="btn btn-primary mb-2"<?php
		$_input = end($this->global->formsStack)["ok"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'class' => NULL,
		))->attributes() ?>>Přidat</button>
        <?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>


    </div>
    <div class="row">
        <div class="col-12">
            <p>Hlavní kořen stromu založíte prázdným ID rodiče a kliknutím na tlačítko přidat.</p>
        </div>
    </div>
    <div class="row border mt-4 d-flex flex-nowrap">
        <div class="font-weight-bold w-5 text-center">Depth</div>
        <div class="font-weight-bold w-auto text-center">Tree Nodes</div>
    </div>
    <div class="data mb-4">
        <div class="row border d-flex flex-nowrap" data-depth="0" id="0">
            <div class="node text-center w-auto">NO DATA</div>
        </div>
    </div>
</div><?php
	}

}
