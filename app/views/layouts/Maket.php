<?= MHtml::doctype('html5') ?>
<html>
	<head>
		<?= MHtml::charset('utf-8') ?>
		<?= MHtml::meta('language', 'ru') ?>
		<?= MHtml::cssFile('/css/main.css') ?>
		<?= MHtml::favicon('/favicon.png'); ?>
		<?= MHtml::meta('viewport', 'width=device-width, initial-scale=1.0') ?>
		<?= MHtml::title($this->title) ?>
	</head>
	<body>
		<div id="container">
			<div id="top">
				<span><?= Micro::getInstance()->config['company'] ?></span> <?= Micro::getInstance()->config['slogan'] ?>
			</div>
			<div id="content">
				<?php $this->widget('Menubar', array('links'=>$this->menu)); ?>
				<?= $content ?>
			</div>
			<div id="footer">
				&copy; <?= Micro::getInstance()->config['company'] ?> <?= date('Y') ?>
			</div>
		</div>
	</body>
</html>