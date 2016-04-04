<?php
use Micro\web\Html\Html;
use Micro\web\Language;

/** @var App\Components\View $this */
/** @var array $blogs */
/** @var integer $page */
/** @var Language $lang */

$this->widget('App\Modules\Blog\Widgets\TopblogsWidget');
echo Html::href('Создать', '/blog/post/create');
?>

<?php
echo $this->widget('\Micro\Widget\ListViewWidget', [
    'data' => $blogs,
    'page' => $page,
    'pathView' => __DIR__ . '/_view.php',
    'paginationConfig' => [
        'url' => '/blog/post/index/'
    ]
]);
?>

<p><?= $lang->hello; ?></p>
