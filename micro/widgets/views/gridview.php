<?php /** MicroGridViewWidgetFile */

use Micro\web\helpers\Html;

/** @var \Micro\widgets\GridViewWidget $this */
/** @var array $keys table keys */
/** @var array $rows table elements */
/** @var integer $rowCount count of rows */
/** @var array $paginationConfig setup pagination widget */
/** @var array $tableConfig setup table */
/** @var array $attributes attributes for table */
/** @var array $attributesCounter attributes for counter */
/** @var string $textCounter text for counter */
/*
$table = [];

$headerCells = [];
foreach ($this->attributeLabels() AS $key) {
    $headerCells[] = ['value'=>$key];
}
$table[] = [ 'cells'=>$headerCells, 'header'=>true ];

foreach ($this->rows AS $row) {
    $compileRow = [];
    foreach ($row AS $cell) {
        $compileRow[] = ['value'=>$cell];
    }
    $table[] = ['cells'=>$compileRow];
}
*/
?>
<?=Html::openTag('div', $attributesCounter)?>
    <?=$textCounter;?><?=$rowCount;?>
<?=Html::closeTag('div')?>
<?=Html::openTag('table', $attributes)?>
    <!-- headers -->
    <!-- filters -->
    <!-- elements -->
<?Html::closeTag('table')?>
<?=$this->widget('Micro\widgets\PaginationWidget',$paginationConfig)?>