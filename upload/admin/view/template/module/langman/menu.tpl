<?php foreach($langman_menu as $menu) { ?>
	<?php if ($menu['active']) { ?>
	<li><a id="langman-menu-active" href="<?= $menu['href'] ?>"><?= $menu['text'] ?></a></li>
	<?php } else { ?>
	<li><a  href="<?= $menu['href'] ?>"><?= $menu['text'] ?></a></li>
	<?php } ?>
<?php  } ?>