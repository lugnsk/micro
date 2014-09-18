<?php /** MicroGridViewWidgetFile */

use Micro\web\helpers\Html;

/** @var \Micro\widgets\GridViewWidget $this */
/** @var array $keys table keys */
/** @var array $rows table elements */
/** @var bool $filters rendered filters */
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
    <?=Html::openTag('tr')?>
        <?php foreach ($tableConfig AS $key=>$row): ?>
            <?=Html::openTag('th')?>
                <?= isset($row['header']) ? $row['header'] : $key ?>
            <?=Html::closeTag('th')?>
        <?php endforeach; ?>
    <?=Html::closeTag('tr')?>

    <?php if ($filters): ?>
        <?=Html::openTag('tr')?>
            <?php foreach ($tableConfig AS $key=>$row): ?>
                <?=Html::openTag('td')?>
                    <?= isset($row['filter']) ? $row['filter'] : null ?>
                <?=Html::closeTag('td')?>
            <?php endforeach; ?>
        <?=Html::closeTag('tr')?>
    <?php endif; ?>

    <?php foreach ($rows AS $elem): ?>
        <?=Html::openTag('tr')?>
            <?php foreach ($tableConfig AS $key=>$row): ?>
                <?=Html::openTag('td')?>
                    <?php if (isset($row['class']) AND is_subclass_of($row['class'], 'Micro\widgets\GridColumn')) { ?>
                        <?= new $row['class']($row + ['str'=>isset($elem) ? $elem : null , 'key'=>$elem['id']]) ?>
                    <?php } elseif (isset($row['value'])) { ?>
                        <?= eval('return "'.$row['value'].'";') ?>
                    <?php } else { ?>
                        <?= isset($elem[$key]) ? $elem[$key] : null ?>
                    <?php } ?>
                <?=Html::closeTag('td')?>
            <?php endforeach; ?>
        <?=Html::closeTag('tr')?>
    <?php endforeach; ?>

<?=Html::closeTag('table')?>
<?=$this->widget('Micro\widgets\PaginationWidget',$paginationConfig)?>