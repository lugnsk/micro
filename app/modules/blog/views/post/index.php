<?php

use \Micro\web\helpers\Html;

/** @var array $blogs */
/** @var integer $pages */
/** @var \Micro\base\Language $lang */

$this->widget('App\modules\blog\widgets\TopblogsWidget');
echo Html::link('создать', '/blog/post/create');

if (!$blogs) {
    ?><p>Ничего не найдено</p><?php
} else {
?>
    <?php foreach ($blogs AS $blog): ?>
        <?= Html::heading(1, Html::link($blog->name, '/blog/post/'.$blog->id)) ?>
        <p><?= $blog->content ?></p>
    <?php endforeach; ?>
    <p>
        <?php for ($page = 0; $page < $pages; $page++): ?>
            <?php if ($page != $_GET['page']): ?>
                <?= Html::openTag('a', ['href'=>'/blog/post/index/'.$blog]) ?>
            <?php endif; ?>

            <?= $page + 1; ?>

            <?php if ($page != $_GET['page']): ?>
                <?= Html::closeTag('a') ?>
            <?php endif; ?>
        <?php endfor; ?>
    </p>
    <p><?= $lang->hello; ?></p>
<?php } ?>