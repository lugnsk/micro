<?php foreach ($blogs AS $blog): ?>
	<h1><?= $blog->name; ?></h1><p><?= $blog->content ?></p>
<?php endforeach; ?>