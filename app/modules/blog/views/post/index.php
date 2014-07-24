<?php $this->widget('App\modules\blog\widgets\Topblogs'); ?>
<a href="/blog/post/create">создать</a>

<?php if(!$blogs): ?>
	<p>Ничего не найдено.</p>
<?php endif; ?>
<?php foreach ($blogs AS $blog): ?>
	<h1><a href="/blog/post/<?= $blog->id; ?>"><?= $blog->name; ?></a></h1><p><?= $blog->content ?></p>
<?php endforeach; ?>

<p>
	<?php for ($page = 0; $page < $pages; $page++): ?>
		<?php if ($page != $_GET['page']): ?>
			<a href="/blog/post/index/<?= $page; ?>">
		<?php endif; ?>

		<?= $page+1; ?>

		<?php if ($page != $_GET['page']): ?>
			</a>
		<?php endif; ?>
	<?php endfor; ?>
</p>

<p><?= $lang->hello; ?></p>