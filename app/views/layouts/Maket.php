<?= MHtml::doctype('html5') ?>
<html>
	<head>
		<?= MHtml::charset('utf-8') ?>
		<?= MHtml::meta('language', 'ru') ?>
		<?= MHtml::cssFile('/css/main.css') ?>
		<?= MHtml::meta('viewport', 'width=device-width, initial-scale=1.0') ?>
		<?= MHtml::title($this->title) ?>
	</head>
	<body>
		<div id="container">
			<div id="top">
				<span><?= Micro::getInstance()->config['company'] ?></span> <?= Micro::getInstance()->config['slogan'] ?>
			</div>
			<div id="content">
				<div class="menu"><?= implode(' ', $this->menu) ?></div>
				<?= $content ?>
			</div>
			<div id="footer">
				&copy; <?= Micro::getInstance()->config['company'] ?> <?= date('Y') ?>
			</div>
		</div>
	</body>
</html>