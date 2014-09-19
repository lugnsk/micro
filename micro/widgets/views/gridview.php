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
echo Html::openTag('div', $attributesCounter);
echo $textCounter;
echo $rowCount;
echo Html::closeTag('div');
echo Html::openTag('table', $attributes);
echo Html::openTag('tr');

foreach ($tableConfig as $key=>$row) {
	echo Html::openTag('th');
	echo isset($row['header']) ? $row['header'] : $key;
	echo Html::closeTag('th');
}
echo Html::closeTag('tr');
if ($filters) {
	echo Html::openTag('tr');
	foreach ($tableConfig as $key=>$row) 
	{
		echo Html::openTag('td');
		echo isset($row['filter']) ? $row['filter'] : null;
		echo Html::closeTag('td');
	}
	echo Html::closeTag('tr');
}
foreach ($rows as $elem) 
{
	echo Html::openTag('tr');
	foreach ($tableConfig AS $key=>$row)
	{
		echo Html::openTag('td');
		if (isset($row['class']) && is_subclass_of($row['class'], 'Micro\widgets\GridColumn')){ 
			echo new $row['class']($row + ['str'=>isset($elem) ? $elem : null , 'key'=>$elem['id']]);
		} elseif (isset($row['value'])) { 
			eval('return "'.$row['value'].'";');
		} else {
			isset($elem[$key]) ? $elem[$key] : null;
		} 
		echo Html::closeTag('td');
	}
	echo Html::closeTag('tr');
}
echo Html::closeTag('table');
echo $this->widget('Micro\widgets\PaginationWidget',$paginationConfig);
