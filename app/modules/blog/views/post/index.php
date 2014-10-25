<?php

use \Micro\wrappers\Html;
use \Micro\web\Language;

/** @var array $blogs */
/** @var integer $pages */
/** @var Language $lang */

$currPage = 0;
if (isset($_GET['page'])) {
    $currPage = $_GET['page'];
}

$this->widget('App\modules\blog\widgets\TopblogsWidget');
echo Html::href('создать', '/blog/post/create');

if (!$blogs) {
    ?><p>Ничего не найдено</p><?php
} else {
    ?>
    <?php foreach ($blogs AS $blog): ?>
        <?= Html::heading(1, Html::link($blog->name, '/blog/post/' . $blog->id)) ?>
        <p><?= $blog->content ?></p>
    <?php endforeach; ?>
    <p>
        <?php for ($page = 0; $page < $pages; $page++): ?>
            <?php if ($page != $currPage): ?>
                <?= Html::openTag('a', ['href' => '/blog/post/index/' . $page]) ?>
            <?php endif; ?>

            <?= $page + 1; ?>

            <?php if ($page != $currPage): ?>
                <?= Html::closeTag('a') ?>
            <?php endif; ?>
        <?php endfor; ?>
    </p>
    <p><?= $lang->hello; ?></p>
<?php } ?>