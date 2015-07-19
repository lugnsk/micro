<?php
use Micro\web\Html;
use Micro\web\Language;

/** @var App\components\View $this */
/** @var array $blogs */
/** @var integer $page */
/** @var Language $lang */

$this->widget('App\modules\blog\widgets\TopblogsWidget');
echo Html::href('Создать', '/blog/post/create');
?>

<?php
echo $this->widget('\Micro\widget\ListViewWidget', [
    'data' => $blogs,
    'page' => $page,
    'pathView' => __DIR__ . '/_view.php',
    'paginationConfig' => [
        'url' => '/blog/post/index/'
    ]
]);
?>

<p><?= $lang->hello; ?></p>