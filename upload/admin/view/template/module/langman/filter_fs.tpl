<form action="<?= $action ?>" method="post">
	<b><?= $entry_view ?>&nbsp;&nbsp;</b>
	<select name="v" onchange="this.form.submit()">
		<?php if ($post['v'] == 2) { ?>
		<option value="1"><?= $text_file ?></option>
		<option value="2" selected="selected"><?= $text_group ?></option>
		<option value="3"><?= $text_directory ?></option>
		<?php } elseif ($post['v'] == 3) { ?>
		<option value="1"><?= $text_file ?></option>
		<option value="2"><?= $text_group ?></option>
		<option value="3" selected="selected"><?= $text_directory ?></option>
		<?php } else { ?>
		<option value="1" selected="selected"><?= $text_file ?></option>
		<option value="2"><?= $text_group ?></option>
		<option value="3"><?= $text_directory ?></option>
		<?php } ?>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if ($post['v'] > 2) { ?>
	<b style="visibility:hidden"><?= $entry_directory ?>&nbsp;&nbsp;</b>
	<select name="d" style="visibility:hidden" onchange="this.form.submit()"> <?php
		foreach($filter as $num => $group){
			if($num == $post['d']) { ?>
			<option value="<?= $num ?>" selected="selected"><?= $num + 1 . '. ' .  $group->directory ?></option> <?php }
			else { ?>
			<option value="<?= $num ?>"><?= $num + 1 . '. ' .  $group->directory ?></option> <?php }
		} ?>
	</select>
	<?php } else { ?>
	<b><?= $entry_directory ?>&nbsp;&nbsp;</b>
	<select name="d" onchange="this.form.submit()"> <?php
		foreach($filter as $num => $group){
			if($num == $post['d']) { ?>
			<option value="<?= $num ?>" selected="selected"><?= $num + 1 . '. ' .  $group->directory ?></option> <?php }
			else { ?>
			<option value="<?= $num ?>"><?= $num + 1 . '. ' .  $group->directory ?></option> <?php }
		} ?>
	</select>
	<?php } ?>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if ($post['v'] > 1) { ?>
	<b style="visibility:hidden"><?= $entry_file ?>&nbsp;&nbsp;</b> 
	<select name="f" style="visibility:hidden" onchange="this.form.submit()"> <?php
		foreach($filter[($post['d'])]->files as $num => $file) {
			$i = $num + 1;
			if($i == $post['f']) { ?>
				<option value="<?=$i;?>" selected="selected"><?= $i . '. ' . $file . '.php' ?></option> <? }
			else { ?>
				<option value="<?=$i;?>"><?= $num + 1 . '. ' . $file . '.php' ?></option> <?php }
		} ?>
	</select>
	<?php } else { ?>
	<b><?= $entry_file ?>&nbsp;&nbsp;</b> 
	<select name="f" onchange="this.form.submit()"> <?php
		foreach($filter[($post['d'])]->files as $num => $file) {
			$i = $num + 1;
			if($i == $post['f']) { ?>
				<option value="<?=$i;?>" selected="selected"><?= $i . '. ' . $file . '.php' ?></option> <? }
			else { ?>
				<option value="<?=$i;?>"><?= $num + 1 . '. ' . $file . '.php' ?></option> <?php }
		} ?>
	</select>
	<?php } ?>
	<input type="hidden" name="m" value="<?= $post['m'] ?>" />
	<input type="hidden" name="p" value="<?= $post['d'] ?>" />
</form>