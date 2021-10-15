<?= $header ?>
<!--<link rel="stylesheet" href="view/stylesheet/langman.css" type="text/css"/>-->
<div id="content">
	<ul class="breadcrumb"><?php
		foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li> <?php } ?>
	</ul>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<?php include('heading.tpl') ?>
		</div>
		<div class="contentes">
			<?php if ($error_db_driver) { ?>
			<h2><?= $error_db_h2 ?></h2>
			<div class="langman-error"><?= $error_db_driver ?></div>

			<?php } else { ?>
			<menu id="langman-menu">
				<?php include('menu.tpl') ?>
			</menu>
			<div class="langman-content">
				<?php ($post['m'] == 'db') ? include('content_db.tpl') : include('content_fs.tpl'); ?>
			</div>
			<menu id="langman-menu">
				<?php include('menu.tpl') ?>
			</menu>
			<?php } ?>
		</div>
		<div class="foot_heading">
			<?php include('heading.tpl') ?>
		</div>
	</div>
</div>
<?= $footer ?>