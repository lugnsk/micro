<?php /** MicroGridViewWidgetFile */
/** @var array $attributesCounter attributes for counter */
/** @var string $counterText text for counter */
/** @var integer $rowCount count of rows */
/** @var array $table table elements */
/** @var array $attributes attributes for table */
/** @var array $paginationConfig setup pagination widget */
?>
<?=\Micro\web\helpers\Html::openTag('div', $attributesCounter)?>
<?=$counterText;?><?=$rowCount;?>
<?=\Micro\web\helpers\Html::closeTag('div')?>

<?=\Micro\web\helpers\Html::table($table, $attributes)?>
<?=$this->widget('Micro\widgets\PaginationWidget',$paginationConfig)?>