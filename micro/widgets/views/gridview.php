<?=\Micro\web\helpers\Html::openTag('div', $attributesCounter)?>
<?=$counterText;?><?=$rowCount;?>
<?=\Micro\web\helpers\Html::closeTag('div')?>

<?=\Micro\web\helpers\Html::table($table, $attributes)?>